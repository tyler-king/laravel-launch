## CDN Cache Control Headers

Configure cache control headers for CDN by defining cache control values per endpoint in your OpenAPI specification.

### Configuration

In your `openapi.json` file, add `x-cache-control` property to any endpoint that needs cache control:

```json
{
  "paths": {
    "/api/users": {
      "get": {
        "controller": "UserController@index",
        "x-cache-control": "public, max-age=3600"
      }
    },
    "/api/posts": {
      "get": {
        "controller": "PostController@index",
        "x-cache-control": "public, max-age=7200"
      }
    }
  }
}
```

### Usage

The middleware is automatically applied to any endpoint that has the `x-cache-control` property defined in the OpenAPI spec. Each endpoint can have its own cache control settings, making it easy to configure different caching strategies per endpoint.

## Request Throttling

Limit the number of requests a user can make to your API endpoints by defining throttling rules in your OpenAPI specification.

### Configuration

In your `openapi.json` file, add `x-throttle` property to any endpoint that needs rate limiting. The value should be in the format `max_attempts,decay_minutes`:

```json
{
  "paths": {
    "/api/sensitive-data": {
      "get": {
        "controller": "DataController@index",
        "x-throttle": "10,1"
      }
    },
    "/api/search": {
      "get": {
        "controller": "SearchController@index",
        "x-throttle": "60,1"
      }
    }
  }
}
```

### Usage

The throttling middleware is automatically applied to any endpoint with the `x-throttle` property. It uses the request fingerprint (typically IP address) to track and limit requests. When the limit is exceeded, a `429 Too Many Attempts` response is returned with `X-RateLimit-Limit` and `X-RateLimit-Remaining` headers.
