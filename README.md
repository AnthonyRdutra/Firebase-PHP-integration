# Firebase PHP Integration

This repository provides a simple structure for integrating **Firebase Realtime Database** using PHP, with organized classes for connecting, reading, writing, and updating data.

## Requirements

- PHP 7.4 or higher  
- Composer  
- Firebase account  
- Firebase project with Realtime Database enabled  
- Firebase service account key file (`.json`)

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/your-repository.git
   cd your-repository
   ```

2. Install dependencies using Composer:
   ```bash
   composer install
   ```

3. Update the key file path in `ConnectDB.php`:
   ```php
   $serviceAccountKeyFilePath = 'YOUR PATH TO THE JSON KEY';
   ```

4. Update your Firebase Realtime Database URL:
   ```php
   ->withDatabaseUri('https://YOUR-RTDABASE.firebaseio.com')
   ```

## Structure

- `ConnectDB.php`: Class responsible for connecting to Firebase and performing CRUD operations.
- `FireBaseAbstract.php`: Abstract class to simplify access to the database methods.
- `vendor/`: Composer directory containing the Firebase SDK.

## How to Use

You can create a new PHP script and extend the `FireBaseAbstract` class to easily make database calls:

```php
require 'path/to/FireBaseAbstract.php';

class MyApp extends FireBaseAbstract {}

$app = new MyApp();

// Add data
$app->add('clients', [
    'name' => 'John Doe',
    'email' => 'john@example.com'
]);

// List all data in the table
$allClients = $app->list('clients');
print_r($allClients);

// Filtered listing (by ID or partial value)
$filtered = $app->list('clients', 'john');

// Update data
$app->update('clients/CLIENT_ID', [
    'name' => 'John Updated'
]);

// Delete data, once you send a null array, that key will be deleted
$app->update('clients/CLIENT_ID', [
    'name' => null
]);

```

## Notes

- All operations are performed using the Firebase Realtime Database and arrays/objects.
- Make sure your database rules allow read and write access during development.
- This structure is ideal for lightweight apps, admin panels, and quick database interactions.

---
