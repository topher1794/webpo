<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="description" content="SwaggerUI" />
  <title>SwaggerUI</title>
  <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@4.5.0/swagger-ui.css" />
</head>

<body>
  <div id="swagger-ui"></div>
  <script src="https://unpkg.com/swagger-ui-dist@4.5.0/swagger-ui-bundle.js" crossorigin></script>
  <script>
    var spec =  {
  "swagger": "2.0",
  "info": {
    "version": "1.0.0",
    "title": "Stock Alignment API",
    "description": "To acquire a bearer token, navigate to https://www.base64encode.org/ and enter your company email address. Subsequently, copy the generated output and utilize it for bearer authentication.\n"
  },
  "host": "https://intra.uratex.com.ph/stockalignproj/",
  "schemes": [
    "http",
  ],
  "securityDefinitions": {
    "Bearer": {
      "type": "apiKey",
      "name": "Authorization",
      "in": "header",
      "description": "Enter the token with the `Bearer: ` value must be combination of username and password in base64 encoded format."
    }
  },
  "paths": {
    "/apitokenv1": {
      "post": {
        "summary": "getting token",
        "security": [
          {
            "Bearer": []
          }
        ],
        "responses": {
          "200": {
            "description": "Will send access token"
          },
          "403": {
            "description": "You do not have necessary permissions for the resource"
          }
        }
      }
    }
    ,"/apirefreshtokenv1": {
      "post": {
        "summary": "refreshing token",
        "security": [
          {
            "Bearer": []
          }
        ],
        "parameters": [
          {
            "name": "refresh_token",
            "in": "formData",
            "description": "Refresh token value",
            "required": true,
            "type": "string"
          },
        ],
        "responses": {
          "200": {
            "description": "Will refresh token"
          },
          "403": {
            "description": "You do not have necessary permissions for the resource"
          }
        }
      }
    }
     ,"/updatestockv1": {
      "post": {
        "summary": "Updating of Stocks",
        "security": [
          {
            "Bearer": []
          }
        ],
        "parameters": [
          {
            "name": "access_token",
            "in": "formData",
            "description": "Refresh token value",
            "required": true,
            "type": "string"
          },
          {
            "name": "materialcode",
            "in": "formData",
            "description": "Material code of products",
            "required": true,
            "type": "string"
          },
          {
            "name": "company",
            "in": "formData",
            "description": "Company name",
            "required": true,
            "type": "string"
          },
        ],
        "responses": {
          "200": {
            "description": "Will refresh token"
          },
          "403": {
            "description": "You do not have necessary permissions for the resource"
          }
        }
      }
    }



  }
  
};

    window.onload = () => {
      window.ui = SwaggerUIBundle({
       spec,
        dom_id: '#swagger-ui',
      });
    };
  </script>
</body>

</html>