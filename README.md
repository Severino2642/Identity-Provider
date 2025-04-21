# Identity-Provider

This REST API allows for the secure registration and login of users, as well as the management of their profiles. It provides a **secure authentication system** based on **tokens** and implements an **email validation mechanism via a PIN code** to ensure the validity of the user.

## 🔑 Key Features

- **Secure User Registration**  
  Users can sign up by providing a valid email address and a secure password. A confirmation email containing a **PIN code** is sent to validate the user's email address.

- **Email Validation via PIN Code**  
  After registration, a **PIN code** is sent to the provided email address. The user must enter this code to validate their email address and activate their account.

- **Secure Login with Token (JWT)**  
  Once the user is validated, they can log in using their credentials. An authentication token (typically **JWT** - JSON Web Token) is generated and returned to the user, allowing secure access for future API requests.

- **User Profile Management and Modification**  
  Users can view and update their profiles through the API, including modifying personal information such as name, surname, email address, etc. All requests require authentication via the access token.

- **API Endpoint Security with Tokens**  
  The **authentication token system** is used to protect sensitive API endpoints. This ensures that only authenticated requests can access or modify user information.

## 🛠️ Technologies Used

- **Backend Framework**: Symfony  
- **Database**: PostgreSQL

## 🎯 Objective

Provide a **secure REST API** that manages user registration, login, and profile management while ensuring the confidentiality and security of data. Ideal for applications requiring robust user management and token-based authentication.

