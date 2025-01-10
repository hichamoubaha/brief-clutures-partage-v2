# Culture Platform V2.0

This platform promotes art and culture with extended features for users, authors, and administrators. It offers functionalities such as user registration, article management, advanced search, comment systems, and more. The project is built with **PHP (OOP)**, **UML**, and **MySQL**.

## Features

### 1. **For Users:**
- **Registration and Login**:
  - Users can register with their **name**, **email**, **password**, and **profile picture**.
  - Secure login to access their personal space.
  - **Email Notifications** upon account creation:
    - **Authors**: Welcome message with an invitation to publish articles.
    - **Readers**: Encouraging message to explore, comment, and add articles to favorites.

- **Personal Information Management**:
  - Update **profile information**, including **password** and **profile picture**.

- **Enhanced Navigation**:
  - **Advanced Search**: Search articles by **keyword** or **author**.
  - **Filter** and **Paginate** articles by **category**.

- **Article Interactions**:
  - **Add comments** to articles.
  - **Like and favorite articles** (with automatic favorites when liked).
  - Option to **download articles as PDF**.

### 2. **For Authors:**
- **Article Management**:
  - **Create**, **modify**, or **delete** articles.
  - Each article must include a **photo**.
  - Associate **multiple tags** with an article.

### 3. **For Administrators:**
- **User Management**:
  - **View user profiles**.
  - **Soft delete** (ban) a user without deleting data.
  - **Manage user roles** (reader, author, administrator).

- **Article and Comment Management**:
  - **Validate or reject** articles submitted by authors.
  - **Delete inappropriate comments**.

- **Category and Tag Management**:
  - Manage **categories** and **tags** associated with articles.

---

## Installation

### Requirements:
- **PHP** (version 7.4 or higher)
- **MySQL** (version 5.7 or higher)
- **Web Server** (Apache/Nginx)

### Steps to Setup:

1. Clone the repository:
   ```bash
   git clone https://github.com/hichamoubaha/brief-clutures-partage-v2.git
