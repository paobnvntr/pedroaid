![image](https://github.com/user-attachments/assets/d2cb27cc-2816-4efc-bb99-a979d5b38a56)
# PedroAID

Welcome to **PedroAID**, a comprehensive web-based portal for legal offices. This platform is designed to facilitate appointment scheduling, manage city ordinances, and handle inquiries efficiently. It is perfect for municipal offices, legal firms, or any organization looking to streamline its administrative processes.

## Table of Contents

- [Overview](#overview)
- [Features](#features)
- [Getting Started](#getting-started)
- [Usage](#usage)
- [Contributing](#contributing)

## Overview

PedroAID is a web-based portal built to optimize the workflow of legal offices and municipalities. It provides a user-friendly interface for handling appointments, document requests, and inquiries. Additionally, PedroAID integrates a natural language understanding (NLU) chatbot for automated responses to common inquiries, streamlining the communication process between staff and the public.

## Features

- **Appointment Scheduling**: Schedule and manage appointments efficiently with an easy-to-use calendar interface.
- **Inquiry Handling**: Address citizen inquiries through a streamlined web portal, integrating a chatbot for quick responses.
- **Document Requests**: Allow users to request and track documents, ensuring transparency and traceability.
- **City Ordinance Management**: Provide access to city ordinances and legal documents, with easy categorization and search functionalities.
- **Chatbot Integration**: Integrated with DialogFlow CX for Natural Language Understanding (NLU), allowing the system to handle common inquiries automatically.
- **User and Service Management**: Admin-side portal for managing users, committees, and legal services provided by the office.

## Getting Started

To set up the PedroAID, follow these steps:

1. **Clone the repository**:
   ```bash
   git clone https://github.com/paobnvntr/pedro-aid.git
   cd pedro-aid
    ```

2. **Install dependencies**:
    ```bash
    composer install
    ```

3. **Copy the `.env` file**:
    ```bash
    cp .env.example .env
    ```

4. **Generate an application key**:
    ```bash
    php artisan key:generate
    ```

5. **Configure your database**:
    Update your `.env` file with your database credentials.

6. **Run the migrations**:
    ```bash
    php artisan migrate
    ```

7. **Start the development server**:
    ```bash
    php artisan serve
    ```
8. **Access the application**: Open your web browser and visit http://localhost:8000 to see the application in action.

## Usage

Once you have the application up and running, you can explore the various features, manage your inventory, process sales, and handle customer orders seamlessly.

## Contributing

Contributions are welcome! If you would like to contribute to the development of this project, please fork the repository and submit a pull request with your enhancements or bug fixes.