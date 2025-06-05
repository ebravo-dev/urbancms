# API Documentation - Blog/Articles

## Base URL
```
http://your-domain.com/api
```

## Articles Endpoints

### 1. Get All Articles
**GET** `/articles`

Returns a paginated list of articles with basic information.

**Query Parameters:**
- `search` (string, optional) - Search in title, description, and keywords
- `date_from` (date, optional) - Filter articles from this date (YYYY-MM-DD)
- `date_to` (date, optional) - Filter articles to this date (YYYY-MM-DD)
- `per_page` (integer, optional) - Number of items per page (default: 10, max: 50)
- `page` (integer, optional) - Page number

**Example Request:**
```bash
GET /api/articles?search=casa&per_page=5&page=1
```

**Example Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Guía Completa para Comprar tu Primera Casa",
      "slug": "guia-completa-para-comprar-tu-primera-casa",
      "description": "Todo lo que necesitas saber antes de comprar tu primera propiedad",
      "publication_date": "2025-06-05T10:00:00Z",
      "publication_date_formatted": "05 Jun 2025",
      "meta_title": "Guía Completa para Comprar tu Primera Casa",
      "meta_description": "Aprende los pasos esenciales...",
      "keywords": ["bienes raíces", "casa", "compra", "inversión"],
      "images": [
        {
          "id": 1,
          "url": "http://domain.com/storage/articles/image1.jpg",
          "display_order": 0
        }
      ],
      "featured_image": "http://domain.com/storage/articles/image1.jpg",
      "content_preview": "El mercado inmobiliario actual presenta oportunidades únicas...",
      "created_at": "2025-06-05T10:00:00Z",
      "updated_at": "2025-06-05T10:00:00Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 5,
    "total": 15,
    "from": 1,
    "to": 5
  },
  "links": {
    "first": "http://domain.com/api/articles?page=1",
    "last": "http://domain.com/api/articles?page=3",
    "prev": null,
    "next": "http://domain.com/api/articles?page=2"
  }
}
```

### 2. Get Single Article
**GET** `/articles/{id_or_slug}`

Returns detailed information about a specific article, including full content and comments.

**Example Request:**
```bash
GET /api/articles/1
# or
GET /api/articles/guia-completa-para-comprar-tu-primera-casa
```

**Example Response:**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Guía Completa para Comprar tu Primera Casa",
    "slug": "guia-completa-para-comprar-tu-primera-casa",
    "description": "Todo lo que necesitas saber antes de comprar tu primera propiedad",
    "publication_date": "2025-06-05T10:00:00Z",
    "publication_date_formatted": "05 Jun 2025",
    "meta_title": "Guía Completa para Comprar tu Primera Casa",
    "meta_description": "Aprende los pasos esenciales...",
    "keywords": ["bienes raíces", "casa", "compra", "inversión"],
    "images": [
      {
        "id": 1,
        "url": "http://domain.com/storage/articles/image1.jpg",
        "display_order": 0
      }
    ],
    "featured_image": "http://domain.com/storage/articles/image1.jpg",
    "content": [
      {
        "type": "heading",
        "content": "Guía Completa para Comprar tu Primera Casa"
      },
      {
        "type": "paragraph",
        "content": "El mercado inmobiliario actual presenta oportunidades únicas..."
      },
      {
        "type": "subtitle",
        "content": "Factores Clave a Considerar"
      },
      {
        "type": "list",
        "content": "Ubicación\nPrecio\nEstado de la propiedad\nFinanciamiento"
      }
    ],
    "content_html": "<h2>Guía Completa...</h2><p>El mercado inmobiliario...</p>...",
    "comments": [
      {
        "id": 1,
        "author_name": "Juan Pérez",
        "author_email": "juan@example.com",
        "content": "Excelente artículo, muy útil",
        "created_at": "2025-06-05T12:00:00Z",
        "created_at_formatted": "05 Jun 2025 12:00"
      }
    ],
    "created_at": "2025-06-05T10:00:00Z",
    "updated_at": "2025-06-05T10:00:00Z"
  }
}
```

### 3. Get Featured Articles
**GET** `/articles/featured`

Returns the latest articles, useful for homepage or featured sections.

**Query Parameters:**
- `limit` (integer, optional) - Number of articles to return (default: 5, max: 10)

**Example Request:**
```bash
GET /api/articles/featured?limit=3
```

**Example Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Guía Completa para Comprar tu Primera Casa",
      "slug": "guia-completa-para-comprar-tu-primera-casa",
      "description": "Todo lo que necesitas saber...",
      "publication_date": "2025-06-05T10:00:00Z",
      "publication_date_formatted": "05 Jun 2025",
      "featured_image": "http://domain.com/storage/articles/image1.jpg",
      "content_preview": "El mercado inmobiliario actual...",
      "keywords": ["bienes raíces", "casa", "compra"]
    }
  ]
}
```

### 4. Get Related Articles
**GET** `/articles/{id_or_slug}/related`

Returns articles related to the specified article based on similar keywords.

**Query Parameters:**
- `limit` (integer, optional) - Number of related articles (default: 3, max: 5)

**Example Request:**
```bash
GET /api/articles/1/related?limit=3
```

**Example Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 2,
      "title": "Tendencias del Mercado Inmobiliario 2025",
      "slug": "tendencias-del-mercado-inmobiliario-2025",
      "description": "Análisis de las tendencias actuales...",
      "publication_date": "2025-06-04T10:00:00Z",
      "publication_date_formatted": "04 Jun 2025",
      "featured_image": "http://domain.com/storage/articles/image2.jpg",
      "content_preview": "Las tendencias del mercado...",
      "keywords": ["mercado", "inmobiliario", "tendencias"]
    }
  ]
}
```

## Content Block Types

The `content` field in articles contains an array of blocks with the following types:

- **`paragraph`** - Regular text paragraph
- **`heading`** - Main heading (H2)
- **`subtitle`** - Subtitle (H3)
- **`quote`** - Blockquote for highlighted text
- **`list`** - Unordered list (items separated by newlines)

## Error Responses

All endpoints return consistent error responses:

```json
{
  "success": false,
  "message": "Error description",
  "errors": {}  // Validation errors if applicable
}
```

## Status Codes

- `200` - Success
- `404` - Article not found
- `422` - Validation error
- `500` - Server error

## Rate Limiting

All API endpoints are rate limited. Check the response headers for rate limit information:
- `X-RateLimit-Limit` - Maximum requests per period
- `X-RateLimit-Remaining` - Remaining requests in current period
- `X-RateLimit-Reset` - Unix timestamp when the rate limit resets
