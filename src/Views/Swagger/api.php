
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
    "title": "Stock Alignment Project",
    "description": "Generated  "
  },
  "host": "intra.uratex.com.ph/stockalignment",
  "schemes": [
    "https",
  ],
//   "securityDefinitions": {
//     "Bearer": {
//       "type": "apiKey",
//       "name": "Authorization",
//       "in": "header",
//       "description": "Enter the token with the `Bearer: ` prefix, e.g. \"Bearer abcde12345\"."
//     }
//   },
  "paths": {
//     "/getToken": {
//       "post": {
//         "summary": "To get Token",
//         "security": [
//           {
//             "Bearer": []
//           }
//         ],
//         "responses": {
//           "200": {
//             "description": "Will send access token"
//           },
//           "403": {
//             "description": "You do not have necessary permissions for the resource"
//           }
//         }
//       }
//     }
//   ,
    "/authentication/checkMDMLogin": {
      "post": {
        "summary": "Get token from MDM api.",
        "security": [
          {
            "Bearer": []
          }
        ],
        "parameters": [
          {
            "name": "emailaddress",
            "in": "formData",
            "description": "Email address (must be base64 decoded) ",
            "required": true,
            "type": "string"
          },
        //   {
        //     "name": "access_token",
        //     "in": "formData",
        //     "description": "Generated token",
        //     "required": true,
        //     "type": "string"
        //   }
        ],
        "responses": {
          "200": {
            "description": "Will send token"
          },
          "403": {
            "description": "You do not have necessary permissions for the resource"
          }
        }
      }
    }
    ,"/mdm/getForApproval": {
      "post": {
        "summary": "Get approval count.",
        "security": [
          {
            "Bearer": []
          }
        ],
        "parameters": [
          {
            "name": "token",
            "in": "formData",
            "description": "Token generated",
            "required": true,
            "type": "string"
          },
       
        ],
        "responses": {
          "200": {
            "description": "Will send an approval count."
          },
          "403": {
            "description": "You do not have necessary permissions for the resource"
          }
        }
      }
    }
    ,"/authentication/login": {
      "post": {
        "summary": ".",
        "security": [
          {
            "Bearer": []
          }
        ],
        "parameters": [
          {
            "name": "token",
            "in": "formData",
            "description": "Token generated",
            "required": true,
            "type": "string"
          },
       
        ],
        "responses": {
          "200": {
            "description": "Will redirect to dashboard page."
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