import React, { useState, useEffect } from 'react'
import { useNavigate } from 'react-router-dom'
import { useAuth } from '../context/AuthContext'
import { usePDFAccess } from '../hooks/usePDFAccess'
import { useReadingProgress } from '../hooks/useReadingProgress'
import { contentAPI } from '../api/content'
import donQuijoteBg from '../assets/don-quijote-bg.png'
import PremiumGateModal from './PremiumGateModal'
import '../styles/hero.css'

function Hero({ onOpenLogin, addToast }) {
    const { user, addToLibrary, isInLibrary, canAccessPremium } = useAuth()
    const navigate = useNavigate()
    const { getCurrentPage } = useReadingProgress()
    const { isPremiumGateOpen, premiumGateData, handleOpenPDF: handleOpenPDFWithGate, closePremiumGate } = usePDFAccess()
    
    const [featuredBook, setFeaturedBook] = useState(null)
    const [loading, setLoading] = useState(true)

    // Cargar el libro Don Quijote de la BD
    useEffect(() => {
        const loadFeaturedBook = async () => {
            try {
                const response = await contentAPI.getLibros({ per_page: 100 })
                const quijote = response.data.data.find(libro => 
                    libro.titulo.toLowerCase().includes('quijote') ||
                    libro.title?.toLowerCase().includes('quijote')
                )
                
                if (quijote) {
                    setFeaturedBook({
                        id: quijote.id,
                        type: 'libro',
                        title: quijote.titulo || quijote.title,
                        titulo: quijote.titulo || quijote.title,
                        author: quijote.autor || quijote.author,
                        autor: quijote.autor || quijote.author,
                        image_url: quijote.image_url || quijote.imagen || quijote.poster,
                        imagen: quijote.image_url || quijote.imagen || quijote.poster,
                        poster: quijote.image_url || quijote.imagen || quijote.poster,
                        is_premium: quijote.is_premium,
                    })
                } else {
                    // Fallback: usar el primer libro o uno por defecto
                    const firstBook = response.data.data[0]
                    if (firstBook) {
                        setFeaturedBook({
                            id: firstBook.id,
                            type: 'libro',
                            title: firstBook.titulo || firstBook.title,
                            titulo: firstBook.titulo || firstBook.title,
                            author: firstBook.autor || firstBook.author,
                            autor: firstBook.autor || firstBook.author,
                            image_url: firstBook.image_url || firstBook.imagen || firstBook.poster,
                            imagen: firstBook.image_url || firstBook.imagen || firstBook.poster,
                            poster: firstBook.image_url || firstBook.imagen || firstBook.poster,
                            is_premium: firstBook.is_premium,
                        })
                    }
                }
            } catch (err) {
                console.error('Error cargando libro featured:', err)
                // Fallback con datos estáticos si la API falla
                setFeaturedBook({
                    id: 1,
                    type: 'libro',
                    title: 'Don Quijote de la Mancha',
                    titulo: 'Don Quijote de la Mancha',
                    author: 'Miguel de Cervantes',
                    autor: 'Miguel de Cervantes',
                    is_premium: true,
                })
            } finally {
                setLoading(false)
            }
        }

        loadFeaturedBook()
    }, [])

    const handleReadNow = async () => {
        if (!featuredBook) return

        // Si el usuario no está autenticado
        if (!user) {
            onOpenLogin()
            return
        }

        // Validar acceso con el servidor primero (navigateOnly=true)
        const result = await handleOpenPDFWithGate({
            type: featuredBook.type,
            id: featuredBook.id,
            title: featuredBook.title,
            navigateOnly: true
        })

        // Si tiene acceso, navegar al reader
        if (result.success) {
            const currentPage = getCurrentPage(featuredBook.type, featuredBook.id)
            navigate(`/reader/${featuredBook.type}/${featuredBook.id}?page=${currentPage}`)
            if (addToast) addToast(`Abriendo ${featuredBook.title}...`, 'success')
        }
        // Si no tiene acceso, usePDFAccess mostrará el modal automáticamente
    }

    const handleAddToList = () => {
        if (!featuredBook) return

        if (!user) {
            onOpenLogin()
            return
        }

        const success = addToLibrary(featuredBook)
        if (success) {
            if (addToast) addToast(`${featuredBook.title} añadido a tu lista`, 'success')
        }
    }

    if (loading) {
        return (
            <section className="hero" style={{ backgroundImage: `url(${donQuijoteBg})` }}>
                <div className="hero__background">
                    <div className="hero__gradient"></div>
                </div>
            </section>
        )
    }

    if (!featuredBook) {
        return null
    }

    const inList = isInLibrary(featuredBook.id, featuredBook.type)

    return (
        <>
            <section className="hero" style={{ backgroundImage: `url(${donQuijoteBg})` }}>
                <div className="hero__background">
                    <div className="hero__gradient"></div>
                </div>

                <div className="hero__content">
                    <div className="hero__info">
                        <h1 className="hero__title">{featuredBook.title}</h1>
                        <div className="hero__meta">
                            <span className="hero__match">98% Coincidencia</span>
                            <span className="hero__year">1605</span>
                            <span className="hero__age">16+</span>
                            <span className="hero__seasons">800 - 1500 paginas</span>
                            <span className="hero__quality">HD</span>
                            {featuredBook.is_premium && <span className="hero__premium">Premium</span>}
                        </div>
                        <p className="hero__description">
                            Alonso Quijano es un hombre tranquilo que busca en los libros de caballerías un mundo que admira. Pero de tanto leer historias de guerreros, batallas, princesas, gigantes, dragones y encantadores, cae en la locura de creer que son ciertas.
                        </p>
                        <div className="hero__buttons">
                            <button className="hero__button hero__button--play" onClick={handleReadNow} disabled={loading}>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    <path d="M19 1H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V3c0-1.1-.9-2-2-2zm0 16H5V3h14v14zm-7-2h6v-2h-6v2zm0-4h6V9h-6v2zm0-4h6V5h-6v2z" fill="black" />
                                </svg>
                                {user ? 'Leer ahora' : 'Empezar a leer'}
                            </button>
                            <button
                                className={`hero__button hero__button--info ${inList ? 'active' : ''}`}
                                onClick={handleAddToList}
                                disabled={loading}
                            >
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                                    {inList ? (
                                        <path d="M20 6L9 17L4 12" stroke="white" strokeWidth="3" strokeLinecap="round" strokeLinejoin="round" />
                                    ) : (
                                        <path d="M12 5V19M5 12H19" stroke="white" strokeWidth="2" strokeLinecap="round" />
                                    )}
                                </svg>
                                {inList ? 'En tu lista' : 'Mi lista'}
                            </button>
                        </div>
                    </div>
                </div>
            </section>

            {/* Modal para mostrar el gate de premium si es necesario */}
            {isPremiumGateOpen && (
                <PremiumGateModal
                    isOpen={isPremiumGateOpen}
                    onClose={closePremiumGate}
                    contentTitle={premiumGateData.contentTitle}
                    requiresLogin={premiumGateData.requiresLogin}
                    requiresPremium={premiumGateData.requiresPremium}
                    isUserAuthenticated={!!user}
                    userRole={user?.role?.name}
                    onLoginClick={() => {
                        closePremiumGate()
                        onOpenLogin()
                    }}
                />
            )}
        </>
    )
}

export default Hero