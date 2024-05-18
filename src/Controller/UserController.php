<?php
declare(strict_types=1);

namespace App\Controller;

use App\Infrastructure\Database\ConnectionProvider;
use App\Model\User;
use App\Model\UserTable;
use App\Utils;

class UserController
{
    private const DATE_TIME_FORMAT = 'Y-m-d';
    private UserTable $table;

    public function __construct()
    {
        $connection = ConnectionProvider::connectDatabase();
        $this->table = new UserTable($connection);
    }

    public function index(): void
    {
        require __DIR__ . '/../View/register_user_form.php';
    }

    public function registerUser(array $data): void
    {
        $birthDate = Utils::parseDateTime($data['birth_date'], self::DATE_TIME_FORMAT);
        $birthDate = $birthDate->setTime(0, 0, 0);

        $user = new User(
            null, 
            $data['first_name'],
            $data['last_name'],
            empty($data['middle_name']) ? null : $data['middle_name'],
            $data['gender'],
            $birthDate,
            $data['email'],
            empty($data['phone']) ? null : $data['phone'],
            null,
        );

        if ($this->table->findByEmail($data['email']) != null) {
            header('Location: ' . '/error_page.php', true, 303);
        } 

        $userId = $this->table->saveUserToDatabase($user);
        $file = $this->downloadImage($userId);

        if ($file != null){
            $this->table->saveAvatarToDatabase($userId, $file);
        }

        $redirectUrl = "/view_user.php?id=$userId";
        header('Location: ' . $redirectUrl, true, 303);
    }

    public function updateUser(array $data): void
    {
        if (isset($_GET['id'])) {
            $id = intval(trim($_GET['id']));
            $user = $this->table->find($id);
        } else {
            header('Location: ' . '/error_page.php', true, 303);
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = $this->updateUsersData($data);
            $id = $user->getId();
            $this->table->updateUser($user); 
            $redirectUrl = "/view_user.php?id=$id";
            header('Location: ' . $redirectUrl, true, 303);        
        } 

        $this->showForm($id, '/../View/update_user_form.php');
    }

    private function updateUsersData(array $data): User{
        $id = (int)$data['user_id'];
        $user = $this->table->find($id);
        $birthDate = Utils::parseDateTime($data['birth_date'], self::DATE_TIME_FORMAT);
        $birthDate = $birthDate->setTime(0, 0, 0);

        if ($this->table->findByEmail($data['email']) != null) {
            header('Location: ' . '/error_page.php', true, 303);
        } 

        if ($user != null) {
            $user->setFirstName($data['first_name']);
            $user->setLastName($data['last_name']);
            $user->setMiddleName(empty($data['middle_name']) ? null : $data['middle_name']);
            $user->setGender($data['gender']);
            $user->setBirthDate($birthDate);
            $user->setEmail(empty($data['email']) ? null : $data['email']);
            $user->setPhone(empty($data['phone']) ? null : $data['phone']);
        } else {
            header('Location: ' . '/error_page.php', true, 303);
        }

        $file = $this->downloadImage($id);

        if ($file != null){
            $user->setAvatarPath($file);
        }
        
        $this->table->updateUser($user); 
        return $user;
    }

    private function downloadImage(int $id): string {
        $uploadfile = __DIR__ . '/../../uploads/avatar';
        $file = null;

        if ($_FILES['avatar_path']['error'] == 0) {
            $extension = $this->getAvatarExtension($_FILES['avatar_path']['type']);
            if ($extension == null) {
                header('Location: ' . '/error_page.php', true, 303);
            } 
            if (move_uploaded_file($_FILES['avatar_path']['tmp_name'], $uploadfile . $id . '.' . $extension)) {
                $file = 'avatar' . $id . '.' . $extension;   
            }
        }
        return $file;
    }

    private function showForm(int $id, string $pathForm): void {
        $user = $this->table->find($id);
        if (is_int($id))
        {
            if ($user != null)
            {
                $userId = $id;
                $firstName = htmlentities((string)$user->getFirstName());
                $lastName = htmlentities((string)$user->getLastName());
                $middleName = htmlentities((string)$user->getMiddleName());
                $gender = htmlentities((string)$user->getGender());
                $birthDate = htmlentities(Utils::convertDateTimeToStringForm($user->getBirthDate()));                    $email = htmlentities((string)$user->getEmail());
                $phone = htmlentities((string)$user->getPhone());
                $avatarPath = htmlentities((string)$user->getAvatarPath());
                require __DIR__ . $pathForm;
            } else {
                echo 'There is not user with ID = ' . ((string) $id);
            }
        } else {
            header('Location: ' . '/error_page.php', true, 303);
        }
    }

    public function deleteUser(): void
    {
        if (isset($_GET['id'])) {
            $id = intval(trim($_GET['id']));
            $user = $this->table->find($id);
            if ($user->getAvatarPath() != null) {
                $this->deleteImage($user);
            }  
        } else {
            require __DIR__ . '/error_page.php';
        }
        if ($user != null) {
            $this->table->deleteUser($user);
            
        } else {
            require __DIR__ . '/error_page.php';
        }
    }

    private function deleteImage(User $user): void
    {
        $avatarPath = $user->getAvatarPath();
        $filePath = __DIR__ . '/../../uploads/' . $avatarPath;
        if (file_exists($filePath)) 
        {
            unlink($filePath);
            echo "File Successfully Delete."; 
        } else {
            echo "File does not exists"; 
        }
    }

    private function getAvatarExtension(string $mimeType): ?string
    {
        $supportedMimeTypes = [
            'image/jpeg' => 'jpeg',
            'image/png' => 'png',
            'image/gif' => 'gif',
        ];
        return $supportedMimeTypes[$mimeType] ?? null;
    }

    public function viewUser(int $id): void
    {
        $this->showForm($id, '/../View/view_user.php');
    }
}