import { useCallback } from "react";
import { useAuth } from "../context/AuthContext";

/**
 * Hook para manejar el progreso de lectura
 * Almacena y recupera el número de página actual de un libro
 */
export const useReadingProgress = () => {
  const { user } = useAuth();

  // Obtener el progreso guardado de un libro
  const getProgress = useCallback(
    (type, id) => {
      if (!user) return { currentPage: 1, lastPageRead: 0 };

      const cacheKey = `reading_progress_${type}_${id}`;
      const cached = localStorage.getItem(cacheKey);

      if (cached) {
        try {
          return JSON.parse(cached);
        } catch (err) {
          console.error("Error parsing reading progress:", err);
          return { currentPage: 1, lastPageRead: 0 };
        }
      }

      return { currentPage: 1, lastPageRead: 0 };
    },
    [user],
  );

  // Guardar el progreso de un libro
  const saveProgress = useCallback(
    (type, id, currentPage, lastPageRead = null) => {
      if (!user) return;

      const cacheKey = `reading_progress_${type}_${id}`;
      const progressData = {
        currentPage: Math.max(1, currentPage),
        lastPageRead: lastPageRead || currentPage,
        lastUpdated: new Date().toISOString(),
        userId: user.id,
      };

      localStorage.setItem(cacheKey, JSON.stringify(progressData));
    },
    [user],
  );

  // Obtener solo la página actual
  const getCurrentPage = useCallback(
    (type, id) => {
      const prog = getProgress(type, id);
      return prog.currentPage || 1;
    },
    [getProgress],
  );

  // Limpiar el progreso (cuando se termina de leer)
  const clearProgress = useCallback(
    (type, id) => {
      if (!user) return;

      const cacheKey = `reading_progress_${type}_${id}`;
      localStorage.removeItem(cacheKey);
    },
    [user],
  );

  return {
    getProgress,
    saveProgress,
    getCurrentPage,
    clearProgress,
  };
};

export default useReadingProgress;
