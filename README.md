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
