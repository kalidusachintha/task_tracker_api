# Task Tracker API - Technical Documentation

## 1. Project Overview

This document outlines the development approach for a Task Tracker API built with Laravel. 
The system allows users to create, view, update, and delete tasks, with each task having a title, description, and status.
The architecture is designed to be scalable for future features like multi-user or team based support

## 2. System Architecture

### 2.1 High-Level Architecture

```
├── API Layer (Controllers)
├── Service Layer
├── Domain Layer (Models)
└── Database Layer
```

### 2.2 Design Patterns Implemented

- **Service Layer Pattern**: Encapsulates business logic
- **DTO Pattern**: For clean data transfer between layers

### 2.3 SOLID Principles Application

- **Single Responsibility**: Each class has one job (controllers handle requests, service handle logic implementations, etc.)
- **Open/Closed**: Extending functionality without modifying existing code (via interfaces)
- **Liskov Substitution**: Implemented through proper use of interfaces
- **Interface Segregation**: Specific interfaces for services
- **Dependency Injection**: Using Laravel's service container for dependency management and depend on abstraction

### 2.4 KISS/DRY Principles Application
- Avoid code duplication and maintain a straightforward implementation

## 3. Implementation Plan

### 3.1 Database Design

**task_statuses table:**
- id (primary key)
- name (string, unique)
- code (string)
- created_at (timestamp)
- updated_at (timestamp)

**tasks table:**
- id (primary key)
- title (string)
- description (text)
- created_at (timestamp)
- updated_at (timestamp)
- user_id (foreign key, nullable, for future integration)
- task_status_id (foreign key, nullable)
- indexes - task_status_id
- indexes - task_status_id, user_id

### 3.2 API Endpoints

| Method | Endpoint          | Description          |
|--------|-------------------|----------------------|
| GET    | /api/v1/tasks     | Get all tasks        |
| GET    | /api/v1/tasks/{id} | Get specific task    |
| POST   | /api/v1/tasks     | Create new task      |
| PUT    | /api/v1/tasks/{id} | Update existing task |
| DELETE | /api/v1/tasks/{id} | Delete a task        |
| GET    | /api/v1/statuses  | Get all statuses     |

### 3.3 Testing Strategy

- **Feature Tests**: For API endpoints and Rate limit.

### 3.4 Development stack
-  Laravel (v12)
-  PHP (v8.2)
-  Mysql
-  Apache
-  Docker

## 4. Development Process and Deliverables

### 4.1 Directory Structure

```
├───DTO
|    └─── TaskDTO.php
├───Enums
|    └─── TaskStatusEnum.php
├───Http
│   ├───Controllers
│   │   └───Api
│   │       └───V1
|   |            └─── TaskController.php
|   |            └─── TaskStatusController.php
│   ├───Requests
│   │   └───Api
│   │       └───V1
|   |            └─── TaskIndexRequest.php
|   |            └─── TaskStoreRequest.php
|   |            └─── TaskUpdateRequest.php
│   └───Resources
│       └───V1
|            └─── TaskResource.php
|            └─── TaskStatusRescourse.php
├───Models
|        └─── Task.php
|        └─── TaskStatus.php
|        └─── User.php
├───Providers
|        └─── AppServiceProvider.php
└───Services
    └───Api
        └───V1
            └───Interfaces
                    └─── TaskServiceInterface.php
                    └─── TaskStatusServiceInterface.php
            └─── TaskService.php
            └─── TaskStatusService.php
```
### 4.2 Scalability Considerations

- **Database Indexing**: To maintain performance as data grows
- **Rate Limiting**: API rate limiting to prevent abuse
- **User/Team Integration**: Database schema and architecture prepared for multi-user environment
- **API Versioning**: Structure allows for versioned endpoints as the API evolves

## 5. Code Quality and Best Practices

- Add static typing (Used **`Larastan`** for type checking).
- Detailed PHPDoc comments
- Maintaining clean code style (Used **`Laravel pint`**)
- Meaningful variable and method naming
- No business logic in controllers (thin controllers, fat models/services).
- Specific error handling and validation.
- Detailed API documentation using **`Swagger Specification`** (Access documentation at `/api/documentation` endpoint) 

## 6. Laravel Best Practices

- Proper use of Laravel ORM.
- Routing with resource controllers.
- Service providers for dependency injections.
- Resource classes for API response.
- Request classes for handle validation

## 7. Future Roadmap Considerations

- Authentication system integration (JWT or Sanctum)
- Role-based access control
- Team-based task organization
- Task assignment to users/teams
- Activity logging and audit trail
- Notifications system
- Caching implementation
