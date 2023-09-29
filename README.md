# NGO management system web service

## API Reference

#### Login (Post)

```https
https://http://127.0.0.1:8000/api/v1/login
```

| Arguments | Type   | Validation   | Description     |
| :-------- | :----- | :----------- | --------------- |
| email     | string | **Required** | admin@gmail.com |
| password  | string | **Required** | asdffdsa        |

## Profile

### Staff Lists

#### User Profile (Get)

```https
    http://127.0.0.1:8000/api/v1/users/profile
```

###### Admin or manager can search by code

| Arguments | Type   | Validation   | Description |
| :-------- | :----- | :----------- | ----------- |
| code      | string | **Nullable** | adkyf2      |

#### Staff lists (Get)

-   Admin can check all staffs from all departments

-   Manager can check all staffs from the same departments

```https
    http://127.0.0.1:8000/api/v1/users/staffs
```

#### Create Staff (Post)

```https
    http://127.0.0.1:8000/api/v1/users/staff/create
```

-   Admin can only create staff.

| Arguments             | Type    | Validation   | Description               |
| :-------------------- | :------ | :----------- | ------------------------- |
| name                  | string  | **Required** | example name              |
| email                 | string  | **Required** | example email             |
| mobile                | string  | **Required** | 09875638472               |
| joinedDate            | date    | **Required** | 2022-04-22                |
| depId                 | integer | **Required** | 3                         |
| position              | string  | **Required** | staff or admin or manager |
| age                   | integer | **Required** | 33                        |
| gender                | enum    | **Required** | male or female            |
| password              | string  | **Required** | password                  |
| password_confirmation | string  | **Required** | password                  |
| role_id               | array   | **Required** | [1,3]                     |

#### Edit Staff (Put)

```https
    http://127.0.0.1:8000/api/v1/users/staff/{id}/edit
```

-   Admin or manager can edit staff's info.

| Arguments | Type    | Validation   | Description               |
| :-------- | :------ | :----------- | ------------------------- |
| name      | string  | **Required** | example name              |
| mobile    | string  | **Required** | 09875638472               |
| depId     | integer | **Required** | 3                         |
| position  | string  | **Required** | staff or admin or manager |
| age       | integer | **Required** | 33                        |
| role_id   | array   | **Required** | [1,3]                     |

#### Delete Staff (Delete)

-   Admin can only delete staff.

```https
    http://127.0.0.1:8000/api/v1/users/staff/{id}/delete
```

### Logout

#### Logout (Post)

```https
    http://127.0.0.1:8000/api/v1/user/logout
```

#### Logout All Device (Post)

```https
    http://127.0.0.1:8000/api/v1/user/logout-all
```

#### Password Change (Post)

```https
    http://127.0.0.1:8000/api/v1/user/change-password
```

| Arguments             | Type   | Validation   | Description      |
| :-------------------- | :----- | :----------- | :--------------- |
| current_password      | string | **Required** | current password |
| password              | string | **Required** | new password     |
| password_confirmation | string | **Required** | new password     |

## Roles

### Roles

#### Role Lists (Get)

-   Admin or manager can check role lists

```https
https://http://127.0.0.1:8000/api/v1/roles
```

#### Create Role (Post)

-   Admin or manager can create role.

```https
https://http://127.0.0.1:8000/api/v1/roles
```

| Arguments     | Type   | Validation   | Description   |
| :------------ | :----- | :----------- | :------------ |
| name          | string | **Required** | example name  |
| label         | string | **Required** | example label |
| permission_id | array  | **Required** | [2,3]         |

#### Edit Role (Put)

-   Admin can only edit role.

```https
https://http://127.0.0.1:8000/api/v1/roles/{id}
```

| Arguments     | Type   | Validation   | Description   |
| :------------ | :----- | :----------- | :------------ |
| name          | string | **Required** | example name  |
| label         | string | **Required** | example label |
| permission_id | array  | **Required** | [2,3]         |

#### Delete Role (Delete)

-   Admin can only delete role.

```https
https://http://127.0.0.1:8000/api/v1/roles/{id}
```

## Permissions

### Permissions

#### Permission Lists (Get)

-   Admin or manager can check permission lists

```https
https://http://127.0.0.1:8000/api/v1/permissions
```

#### Create Permissions (Post)

-   Admin or manager can create permissions.

```https
https://http://127.0.0.1:8000/api/v1/permissions
```

| Arguments | Type   | Validation   | Description   |
| :-------- | :----- | :----------- | :------------ |
| name      | string | **Required** | example name  |
| label     | string | **Required** | example label |
| role_id   | array  | **Required** | [2,3]         |

#### Edit Permission (Put)

-   Admin or manager can edit permission.

```https
https://http://127.0.0.1:8000/api/v1/permissions/{id}
```

| Arguments | Type   | Validation   | Description   |
| :-------- | :----- | :----------- | :------------ |
| name      | string | **Required** | example name  |
| label     | string | **Required** | example label |
| role_id   | array  | **Required** | [2,3]         |

#### Delete Permissions (Delete)

-   Admin can only delete permissions.

```https
https://http://127.0.0.1:8000/api/v1/permissions/{id}
```

## Departments

### Departments

#### Department Lists (Get)

-   Admin or manager can check department lists

```https
https://http://127.0.0.1:8000/api/v1/departments
```

#### Create Department (Post)

-   Admin or manager can create department.

```https
https://http://127.0.0.1:8000/api/v1/departments
```

| Arguments | Type   | Validation   | Description   |
| :-------- | :----- | :----------- | :------------ |
| name      | string | **Required** | example name  |
| label     | string | **Required** | example label |

#### Edit Department (Put)

-   Admin or manager can edit department.

```https
https://http://127.0.0.1:8000/api/v1/departments/{id}
```

| Arguments | Type   | Validation   | Description   |
| :-------- | :----- | :----------- | :------------ |
| name      | string | **Required** | example name  |
| label     | string | **Required** | example label |

#### Delete Permissions (Delete)

-   Admin can only delete department.

```https
https://http://127.0.0.1:8000/api/v1/departments/{id}
```
