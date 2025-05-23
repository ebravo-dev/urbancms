# API Documentation for Properties

## Base URL
```
https://urbancms.test/api
```

## Authentication
The API provides two options for authentication:

### Option 1: Public API (No Authentication)
By default, the API endpoints are public and require no authentication.

### Option 2: API Token Authentication
For better security, you can activate the API token authentication by uncommenting the middleware section in the routes/api.php file.

When using API token authentication, include the token in the request headers:

```
X-API-TOKEN: urbanCMS-api-token-2025
```

> **Note:** You can change the API token in your .env file by setting the API_TOKEN variable.

## Rate Limiting

The API implements rate limiting to prevent abuse. By default, it limits requests to 60 per minute per IP address. The following headers are included in API responses:

- `X-RateLimit-Limit`: Maximum number of requests allowed per time window
- `X-RateLimit-Remaining`: Number of requests remaining in the current time window
- `X-RateLimit-Reset`: Time in seconds until the rate limit resets

Exceeding the rate limit will result in a `429 Too Many Requests` response.

## Endpoints

### Test API Connectivity

**URL:** `/ping`  
**Method:** `GET`  
**Auth Required:** No (by default)

Tests API connectivity and returns the current server time.

#### Example Response
```json
{
  "message": "pong",
  "status": "success",
  "timestamp": "2025-05-23T10:30:00.000000Z"
}
```

### List Properties
Returns a paginated list of properties with filters.

**URL:** `/properties`  
**Method:** `GET`  
**Auth Required:** No (by default)

#### Query Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `per_page` | integer | Number of results per page (default: 10) |
| `page` | integer | Page number for pagination |
| `is_for_sale` | boolean | Filter by property type (`true` for sale, `false` for rent) |
| `min_investment` | number | Minimum price/investment amount |
| `max_investment` | number | Maximum price/investment amount |
| `location` | string | Search term for location |
| `feature` | string | Search term for features |
| `sort_by` | string | Field to sort by (options: `created_at`, `investment`, `is_for_sale`) |
| `sort_direction` | string | Sort direction (options: `asc`, `desc`) |

#### Example Request
```
GET /api/properties?is_for_sale=true&min_investment=500000&sort_by=investment&sort_direction=desc&per_page=5&page=1
```

#### Example Response
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
        "Estacionamiento",
        "Jardín",
        "Alberca"
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

### Get Property Details
Returns detailed information about a specific property.

**URL:** `/properties/{id}`  
**Method:** `GET`  
**Auth Required:** No (by default)

#### URL Parameters
| Parameter | Description |
|-----------|-------------|
| `id` | Property ID |

#### Example Request
```
GET /api/properties/1
```

#### Example Response
```json
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
    "Estacionamiento",
    "Jardín",
    "Alberca"
  ],
  "investment": 2500000,
  "investment_formatted": "$ 2,500,000.00 MXN",
  "images": [
    "https://urbancms.test/storage/property-images/image1.jpg",
    "https://urbancms.test/storage/property-images/image2.jpg",
    "https://urbancms.test/storage/property-images/image3.jpg",
    "https://urbancms.test/storage/property-images/image4.jpg"
  ],
  "created_at": "2025-05-15T14:30:00.000000Z",
  "updated_at": "2025-05-15T14:30:00.000000Z"
}
```

## Security Considerations

When implementing this API in production environments, consider the following security measures:

### 1. API Token Authentication
The simplest security measure provided is API token authentication. This requires clients to include a predefined token with each request.

### 2. CORS Configuration
Configure CORS (Cross-Origin Resource Sharing) to restrict which domains can access your API.

### 3. Rate Limiting
Implement rate limiting to prevent abuse of your API.

### 4. IP Whitelisting
Restrict API access to specific IP addresses if the frontend application has a fixed IP.

### 5. OAuth 2.0 or JWT Authentication
For more robust security, consider implementing OAuth 2.0 or JWT authentication.
