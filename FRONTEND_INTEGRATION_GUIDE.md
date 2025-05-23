# API Integration Guide for Frontend Team

## Overview

I've created a RESTful API for accessing property listings data from our Urban CMS system. The API provides endpoints to fetch both paginated property listings and individual property details.

## API Endpoints

### Base URL
```
https://urbancms.test/api
```

### Available Endpoints

1. **Test Connectivity**
   - URL: `GET /api/ping`
   - Purpose: Verify API connection and server status

2. **List Properties (Paginated)**
   - URL: `GET /api/properties`
   - Query parameters:
     - `per_page`: Number of results per page (default: 10)
     - `page`: Page number
     - `is_for_sale`: Filter by property type (`true` for sale, `false` for rent)
     - `min_investment` & `max_investment`: Filter by price range
     - `location`: Search by location text
     - `feature`: Search by property features
     - `sort_by`: Field to sort by (`created_at`, `investment`, `is_for_sale`)
     - `sort_direction`: Sort direction (`asc` or `desc`)

3. **Get Single Property**
   - URL: `GET /api/properties/{id}`
   - Parameter: `id` (Property ID)

## Response Format

The API returns data in JSON format with the following structure for property listings:

```json
{
  "data": [
    {
      "id": 1,
      "is_for_sale": true,
      "type": "Venta",
      "location": {
        "line1": "Av. Insurgentes Sur 123",
        "line2": "Col. Del Valle",
        "line3": "Ciudad de México, CDMX"
      },
      "google_maps_url": "https://maps.google.com/...",
      "features": [
        "3 Habitaciones",
        "2 Baños",
        "Estacionamiento"
      ],
      "investment": 2500000,
      "investment_formatted": "$ 2,500,000.00 MXN",
      "images": [
        "https://urbancms.test/storage/property-images/image1.jpg",
        "https://urbancms.test/storage/property-images/image2.jpg"
      ],
      "created_at": "2025-05-15T14:30:00.000000Z",
      "updated_at": "2025-05-15T14:30:00.000000Z"
    },
    // More properties...
  ],
  "pagination": {
    "total": 25,
    "per_page": 5,
    "current_page": 1,
    "last_page": 5,
    "from": 1,
    "to": 5
  }
}
```

## Security Considerations

For the initial implementation, the API is public and does not require authentication. In the future, we may implement either of these security methods:

1. **API Token Authentication**
   - When implemented, add this header:
   - `X-API-TOKEN: urbanCMS-api-token-2025`

2. **CORS Protection**
   - The frontend application's origin must be in the allowed origins list
   - Currently allowed: `http://localhost:3000`, `http://frontend.example.com`

3. **Rate Limiting**
   - Current limit: 60 requests per minute per IP address

## Testing

To test the API connection, make a request to:
```
GET /api/ping
```

Expected response:
```json
{
  "message": "pong",
  "status": "success",
  "timestamp": "2025-05-23T10:30:00.000000Z"
}
```

## Complete Documentation

For complete API documentation, please refer to the [API_DOCUMENTATION.md](API_DOCUMENTATION.md) file in the root directory of the project.

If you encounter any issues or need additional endpoints, please let us know.
