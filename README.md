AgroilAPI v1

**Start here** [Overview](#overview) [Authentication](#authentication) [Quickstart](#quickstart) **Users** [POSTAuthenticate](#auth) [GETList users](#list-users) [POSTRegister user](#register) **Reference** [Errors](#errors) [Security](#security)

# Build on the Agroil API

Authenticate users, list accounts and register new team members through a small, predictable REST API.

Base URL{base_url}/api/v1

AuthenticationBearer token

Response formatapplication/json

Configure `base_url` and `accessKey` as environment variables in Postman so every request below works unchanged across environments.

## Authentication

Call [`POST /api/v1/auth`](#auth) with an email and password to receive an access token. Send that token in the `Authorization` header on every protected request.

HTTP header

```
Authorization: Bearer <your-access-token>
```

Requests without a valid token to a protected endpoint are rejected. Only the authentication endpoint is public.

## Quickstart

1. **Authenticate.** Send your credentials to `POST /api/v1/auth`.
2. **Store the token.** Save the token from the response in the `accessKey` environment variable.
3. **List users.** Call `GET /api/v1/users` with the Bearer header to confirm access.
4. **Register a user.** Call the registration endpoint with the creator's UUID and the new user's details.

## Authenticate user

Exchanges a registered user's email and password for an access token.

POST`/api/v1/auth`Public

### Request body

Content type: `multipart/form-data`

| Field | Type | Description |
| --- | --- | --- |
| `email`required | string | Registered email address. |
| `password`required | string | The user's password. |

### Response

On success the API returns an access token. Store the token it returns in `accessKey`.

The exact response structure is not yet documented. Confirm it against the API implementation before publishing.

## List users

Retrieves the registered users available through this endpoint.

GET`/api/v1/users`Bearer token

### Headers

| Header | Value |
| --- | --- |
| `Authorization` | `Bearer <your-access-token>` |
| `Accept` | `application/json` |

### Request body

None.

### Response

A JSON array, or a paginated collection of user objects, depending on the implementation. Each user may include profile and account information.

Responses must never include passwords or password hashes.

## Register user

Creates a new user account. `createdBy` is the UUID of the user performing the registration.

POST`/api/v1/user/{createdBy}/create/{createdBy}`Bearer token

The current route repeats `{createdBy}` twice. Use the same creator UUID in both positions until the route is simplified.

### Headers

| Header | Value |
| --- | --- |
| `Authorization` | `Bearer <your-access-token>` |
| `Accept` | `application/json` |

### Request fields

| Field | Type | Description |
| --- | --- | --- |
| `name`required | string | Full display name. |
| `username`required | string | Unique username. |
| `first_name`required | string | First name. |
| `last_name`required | string | Last name. |
| `middle_name`optional | string | Middle name. |
| `gender`required | string | Gender. |
| `date_of_birth`required | string | Format `MM-DD-YYYY`. |
| `user_type`required | integer | User role or type code. |
| `email`required | string | Email address. |
| `password`required | string | Initial password. |
| `mobile_phone_number`required | string | Mobile number. |
| `created_by`optional | UUID | Creator UUID. May be derived from the path. |

### Response

Returns confirmation and the created user's details. Document the exact schema and HTTP status code from the live API.

## Errors

The API uses standard HTTP status codes. Confirm the exact error body against the implementation.

| Status | Meaning | What to do |
| --- | --- | --- |
| `400` | Invalid or missing fields | Check required fields and the `MM-DD-YYYY` date format. |
| `401` | Missing or invalid token | Authenticate again and resend the Bearer token. |
| `403` | Not permitted | Use an account allowed to list or register users. |
| `409` | Duplicate username or email | Choose a unique username and email. |

## Security

- Never commit real passwords, access tokens or private credentials to source control.
- Keep API URLs and credentials in environment variables.
- Never return plaintext passwords or password hashes in user responses.
- Check permissions on the server for user registration and user listing.
- Derive the creator's identity from the authenticated user instead of trusting a client-supplied UUID.

Agroil API v1 · Documentation
