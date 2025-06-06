// Ejemplo de integración con la API de Blog/Artículos
// Este archivo muestra cómo el frontend puede consumir la API

const API_BASE = '/api'; // Ajustar según tu configuración

// =====================================
// CONSUMIR ARTÍCULOS
// =====================================

/**
 * Obtener lista de artículos con paginación
 */
async function getArticles(options = {}) {
  const params = new URLSearchParams();
  
  if (options.search) params.append('search', options.search);
  if (options.dateFrom) params.append('date_from', options.dateFrom);
  if (options.dateTo) params.append('date_to', options.dateTo);
  if (options.perPage) params.append('per_page', options.perPage);
  if (options.page) params.append('page', options.page);

  try {
    const response = await fetch(`${API_BASE}/articles?${params}`, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      }
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Error fetching articles:', error);
    throw error;
  }
}

/**
 * Obtener un artículo específico por ID o slug
 */
async function getArticle(idOrSlug) {
  try {
    const response = await fetch(`${API_BASE}/articles/${idOrSlug}`, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      }
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Error fetching article:', error);
    throw error;
  }
}

/**
 * Obtener artículos destacados/recientes
 */
async function getFeaturedArticles(limit = 5) {
  try {
    const response = await fetch(`${API_BASE}/articles/featured?limit=${limit}`, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      }
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Error fetching featured articles:', error);
    throw error;
  }
}

/**
 * Obtener artículos relacionados
 */
async function getRelatedArticles(articleIdOrSlug, limit = 3) {
  try {
    const response = await fetch(`${API_BASE}/articles/${articleIdOrSlug}/related?limit=${limit}`, {
      method: 'GET',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      }
    });

    if (!response.ok) {
      throw new Error(`HTTP error! status: ${response.status}`);
    }

    const data = await response.json();
    return data;
  } catch (error) {
    console.error('Error fetching related articles:', error);
    throw error;
  }
}

// =====================================
// CREAR COMENTARIOS
// =====================================

/**
 * Crear un comentario en un artículo
 */
async function createComment(articleIdOrSlug, commentData) {
  try {
    const response = await fetch(`${API_BASE}/articles/${articleIdOrSlug}/comments`, {
      method: 'POST',
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        author_name: commentData.authorName,
        author_email: commentData.authorEmail,
        content: commentData.content
      })
    });

    const data = await response.json();

    if (!response.ok) {
      // Manejar errores de validación u otros errores
      if (response.status === 422) {
        console.error('Validation errors:', data.errors);
      }
      throw new Error(data.message || `HTTP error! status: ${response.status}`);
    }

    return data;
  } catch (error) {
    console.error('Error creating comment:', error);
    throw error;
  }
}

// =====================================
// EJEMPLOS DE USO
// =====================================

// Ejemplo: Cargar lista de artículos
async function loadArticlesList() {
  try {
    const articlesData = await getArticles({
      search: 'casa',
      perPage: 10,
      page: 1
    });

    console.log('Articles:', articlesData.data);
    console.log('Pagination:', articlesData.meta);
    
    // Aquí renderizarías los artículos en tu frontend
    renderArticles(articlesData.data);
    renderPagination(articlesData.meta, articlesData.links);
  } catch (error) {
    console.error('Error loading articles:', error);
    showErrorMessage('Error cargando artículos');
  }
}

// Ejemplo: Cargar artículo individual
async function loadSingleArticle(slug) {
  try {
    const articleData = await getArticle(slug);
    const article = articleData.data;

    console.log('Article:', article);
    console.log('Content blocks:', article.content);
    console.log('Comments:', article.comments);
    
    // Aquí renderizarías el artículo en tu frontend
    renderArticleDetail(article);
    renderArticleContent(article.content);
    renderComments(article.comments);
    
    // Cargar artículos relacionados
    const relatedData = await getRelatedArticles(slug);
    renderRelatedArticles(relatedData.data);
  } catch (error) {
    console.error('Error loading article:', error);
    showErrorMessage('Error cargando artículo');
  }
}

// Ejemplo: Manejar formulario de comentarios
async function handleCommentForm(event, articleSlug) {
  event.preventDefault();
  
  const formData = new FormData(event.target);
  const commentData = {
    authorName: formData.get('author_name'),
    authorEmail: formData.get('author_email'),
    content: formData.get('content')
  };

  try {
    // Mostrar loading
    showLoadingState(true);
    
    const result = await createComment(articleSlug, commentData);
    
    console.log('Comment created:', result.data);
    
    // Limpiar formulario
    event.target.reset();
    
    // Mostrar mensaje de éxito
    showSuccessMessage('Comentario enviado exitosamente');
    
    // Agregar comentario a la lista (opcional, o recargar toda la lista)
    addCommentToList(result.data);
    
  } catch (error) {
    console.error('Error creating comment:', error);
    showErrorMessage('Error enviando comentario: ' + error.message);
  } finally {
    showLoadingState(false);
  }
}

// =====================================
// FUNCIONES DE RENDERIZADO (EJEMPLO)
// =====================================

function renderArticles(articles) {
  // Implementar renderizado de lista de artículos
  console.log('Rendering articles:', articles);
}

function renderArticleDetail(article) {
  // Implementar renderizado de artículo individual
  console.log('Rendering article detail:', article);
}

function renderArticleContent(contentBlocks) {
  // Renderizar los bloques de contenido JSON como HTML
  const contentHtml = contentBlocks.map(block => {
    switch (block.type) {
      case 'heading':
        return `<h2>${escapeHtml(block.content)}</h2>`;
      case 'subtitle':
        return `<h3>${escapeHtml(block.content)}</h3>`;
      case 'paragraph':
        return `<p>${escapeHtml(block.content)}</p>`;
      case 'quote':
        return `<blockquote>${escapeHtml(block.content)}</blockquote>`;
      case 'list':
        const items = block.content.split('\n').filter(item => item.trim());
        const listItems = items.map(item => `<li>${escapeHtml(item.trim())}</li>`).join('');
        return `<ul>${listItems}</ul>`;
      default:
        return `<p>${escapeHtml(block.content)}</p>`;
    }
  }).join('');

  // Insertar en el DOM
  document.getElementById('article-content').innerHTML = contentHtml;
}

function renderComments(comments) {
  // Implementar renderizado de comentarios
  console.log('Rendering comments:', comments);
}

function renderPagination(meta, links) {
  // Implementar renderizado de paginación
  console.log('Rendering pagination:', meta, links);
}

function renderRelatedArticles(articles) {
  // Implementar renderizado de artículos relacionados
  console.log('Rendering related articles:', articles);
}

function addCommentToList(comment) {
  // Agregar nuevo comentario a la lista existente
  console.log('Adding comment to list:', comment);
}

// =====================================
// FUNCIONES AUXILIARES
// =====================================

function escapeHtml(text) {
  const map = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
  };
  return text.replace(/[&<>"']/g, m => map[m]);
}

function showLoadingState(isLoading) {
  // Mostrar/ocultar indicador de carga
  console.log('Loading state:', isLoading);
}

function showSuccessMessage(message) {
  // Mostrar mensaje de éxito
  console.log('Success:', message);
}

function showErrorMessage(message) {
  // Mostrar mensaje de error
  console.error('Error:', message);
}

// =====================================
// INICIALIZACIÓN
// =====================================

// Ejemplo de inicialización cuando la página carga
document.addEventListener('DOMContentLoaded', function() {
  // Si estamos en la página de lista de artículos
  if (document.getElementById('articles-list')) {
    loadArticlesList();
  }
  
  // Si estamos en la página de un artículo específico
  const articleSlug = document.querySelector('[data-article-slug]')?.dataset.articleSlug;
  if (articleSlug) {
    loadSingleArticle(articleSlug);
  }
  
  // Configurar formulario de comentarios
  const commentForm = document.getElementById('comment-form');
  if (commentForm && articleSlug) {
    commentForm.addEventListener('submit', (e) => handleCommentForm(e, articleSlug));
  }
});
