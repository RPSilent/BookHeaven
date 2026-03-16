import React, { useState, useEffect } from 'react'
import { useParams, useNavigate, useSearchParams } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'
import PremiumGateModal from '../components/PremiumGateModal'
import { getPdfServiceUrl } from '../utils/pdfUtils'
import { useReadingProgress } from '../hooks/useReadingProgress'
import '../styles/reader.css'

function ReaderPage() {
    const { type, id } = useParams()
    const navigate = useNavigate()
    const { user } = useAuth()
    const [searchParams] = useSearchParams()
    const { getCurrentPage, saveProgress } = useReadingProgress()

    const [loading, setLoading] = useState(true)
    const [pdfUrl, setPdfUrl] = useState(null)
    const [accessError, setAccessError] = useState(null) // null | 'NOT_AUTHENTICATED' | 'REQUIRES_PREMIUM' | 'NOT_FOUND' | 'ERROR'
    const [_title, _setTitle] = useState('Cargando...')
    const [isFullscreen, setIsFullscreen] = useState(false)
    const [currentPage, setCurrentPage] = useState(1)
    const [isPremiumModalOpen, setIsPremiumModalOpen] = useState(false)

    useEffect(() => {
        const loadPdf = async () => {
            const token = localStorage.getItem('auth_token')

            // Verificar si el usuario está autenticado antes de intentar cargar
            if (!token) {
                setAccessError('NOT_AUTHENTICATED')
                setLoading(false)
                return
            }

            try {
                const url = getPdfServiceUrl(type, id)

                const response = await fetch(url, {
                    headers: {
                        'Accept': 'application/pdf, application/json',
                        'Authorization': `Bearer ${token}`
                    }
                })

                if (!response.ok) {
                    if (response.status === 403) {
                        // Parsear el JSON de error del backend
                        try {
                            const errorData = await response.json()
                            setAccessError(errorData.code || 'REQUIRES_PREMIUM')
                            if ((errorData.code || 'REQUIRES_PREMIUM') === 'REQUIRES_PREMIUM') {
                                setIsPremiumModalOpen(true)
                            }
                        } catch {
                            setAccessError('REQUIRES_PREMIUM')
                            setIsPremiumModalOpen(true)
                        }
                    } else if (response.status === 404) {
                        setAccessError('NOT_FOUND')
                    } else {
                        setAccessError('ERROR')
                    }
                    setLoading(false)
                    return
                }

                const blob = await response.blob()
                const blobUrl = URL.createObjectURL(blob)
                setPdfUrl(blobUrl)

                // Obtener la página de los parámetros o del progreso guardado
                const pageParam = searchParams.get('page');
                const pageToLoad = pageParam ? parseInt(pageParam, 10) : getCurrentPage(type, id);
                setCurrentPage(pageToLoad);

                setLoading(false)
            } catch (error) {
                console.error('Error loading PDF:', error)
                setAccessError('ERROR')
                setLoading(false)
            }
        }

        loadPdf()

        return () => {
            if (pdfUrl) URL.revokeObjectURL(pdfUrl)
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [type, id])

    // Guardar el progreso periódicamente
    useEffect(() => {
        const timer = setInterval(() => {
            saveProgress(type, id, currentPage);
        }, 30000); // Cada 30 segundos

        return () => clearInterval(timer);
    }, [type, id, currentPage, saveProgress]);

    // Guardar progreso cuando el usuario sale del reader
    useEffect(() => {
        const handleBeforeUnload = () => {
            saveProgress(type, id, currentPage);
        };

        window.addEventListener('beforeunload', handleBeforeUnload);
        return () => window.removeEventListener('beforeunload', handleBeforeUnload);
    }, [type, id, currentPage, saveProgress]);

    const toggleFullscreen = () => {
        if (!document.fullscreenElement) {
            document.documentElement.requestFullscreen()
            setIsFullscreen(true)
        } else {
            if (document.exitFullscreen) {
                document.exitFullscreen()
                setIsFullscreen(false)
            }
        }
    }

    if (loading) {
        return (
            <div className="reader-loading">
                <div className="book-loader">
                    <div className="book-page"></div>
                    <div className="book-page"></div>
                    <div className="book-page"></div>
                </div>
                <h2>Preparando tu lectura...</h2>
                <p>BookHeaven está optimizando el documento para ti.</p>
            </div>
        )
    }

    // Estado de error de acceso
    if (accessError) {
        const isAuthError = accessError === 'NOT_AUTHENTICATED'
        const isPremiumError = accessError === 'REQUIRES_PREMIUM'

        // Si es un error de premium, mostrar el modal
        if (isPremiumError && user) {
            return (
                <>
                    <PremiumGateModal
                        isOpen={isPremiumModalOpen}
                        onClose={() => {
                            setIsPremiumModalOpen(false)
                            navigate(-1)
                        }}
                        contentTitle={`${type === 'libro' ? 'Libro' : type === 'manga' ? 'Manga' : 'Cómic'} Premium`}
                        isUserAuthenticated={!!user}
                        userRole={user?.role?.name}
                        onLoginClick={() => {
                            setIsPremiumModalOpen(false)
                            navigate('/', { state: { openLogin: true } })
                        }}
                    />
                </>
            )
        }

        return (
            <div className="reader-loading" style={{ gap: '16px' }}>
                <div style={{ fontSize: '52px', lineHeight: 1 }}>
                    {isAuthError ? (
                        <svg width="52" height="52" viewBox="0 0 52 52" fill="none">
                            <rect x="8" y="24" width="36" height="24" rx="4" fill="rgba(212,167,106,0.15)" stroke="#D4A76A" strokeWidth="2"/>
                            <path d="M16 24V18a10 10 0 0 1 20 0v6" stroke="#D4A76A" strokeWidth="2" fill="none" strokeLinecap="round"/>
                            <circle cx="26" cy="36" r="3" fill="#D4A76A"/>
                        </svg>
                    ) : (
                        <svg width="52" height="52" viewBox="0 0 52 52" fill="none">
                            <circle cx="26" cy="26" r="22" stroke="rgba(212,167,106,0.4)" strokeWidth="2"/>
                            <path d="M26 16v14" stroke="#D4A76A" strokeWidth="2.5" strokeLinecap="round"/>
                            <circle cx="26" cy="36" r="2.5" fill="#D4A76A"/>
                        </svg>
                    )}
                </div>
                <h2 style={{ color: '#FFFBF5', margin: 0 }}>
                    {isAuthError ? 'Inicia sesión para leer'
                        : accessError === 'NOT_FOUND' ? 'Archivo no encontrado'
                        : 'No se pudo cargar el contenido'}
                </h2>
                <p style={{ color: 'rgba(232,220,200,0.5)', margin: 0, maxWidth: '340px', textAlign: 'center', fontSize: '14px' }}>
                    {isAuthError ? 'Necesitas una cuenta para acceder a este contenido.'
                        : accessError === 'NOT_FOUND' ? 'El archivo PDF de este contenido no está disponible.'
                        : 'Ocurrió un error al intentar cargar el PDF. Inténtalo de nuevo.'}
                </p>
                <div style={{ display: 'flex', gap: '12px', flexWrap: 'wrap', justifyContent: 'center' }}>
                    <button
                        onClick={() => navigate('/')}
                        style={{ padding: '11px 22px', background: 'linear-gradient(135deg,#D4A76A,#C49050)', border: 'none', borderRadius: '10px', color: '#1a0f04', fontWeight: 700, cursor: 'pointer', fontSize: '14px', fontFamily: 'inherit' }}
                    >
                        {isAuthError ? 'Ir al inicio' : 'Volver al inicio'}
                    </button>
                    <button
                        onClick={() => navigate(-1)}
                        style={{ padding: '11px 22px', background: 'rgba(212,167,106,0.08)', border: '1px solid rgba(212,167,106,0.2)', borderRadius: '10px', color: 'rgba(212,167,106,0.8)', fontWeight: 600, cursor: 'pointer', fontSize: '14px', fontFamily: 'inherit' }}
                    >
                        Volver atrás
                    </button>
                </div>
            </div>
        )
    }

    return (
        <div className={`reader-container ${isFullscreen ? 'is-fullscreen' : ''}`}>
            <header className="reader-header">
                <div className="reader-header-left">
                    <button className="btn-back" onClick={() => navigate(-1)}>
                        <span className="icon">←</span>
                        <span className="text">Volver</span>
                    </button>
                    <div className="reader-title-group">
                        <span className="content-type-badge">{type}</span>
                        <h1>Lectura en curso</h1>
                    </div>
                </div>

                <div className="reader-controls">
                    <button
                        className="control-btn"
                        onClick={toggleFullscreen}
                        title="Pantalla completa"
                    >
                        {isFullscreen ? '📺' : '🖥️'}
                    </button>
                    <button className="btn-exit" onClick={() => navigate(-1)}>
                        Cerrar Sesión de Lectura
                    </button>
                </div>
            </header>

            <main className="reader-viewport">
                <iframe
                    src={`${pdfUrl}#page=${currentPage}&toolbar=0&navpanes=0&scrollbar=1`}
                    title="PDF Reader"
                    className="pdf-iframe"
                />
            </main>
        </div>
    )
}

export default ReaderPage
