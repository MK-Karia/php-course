<div>
        <label for="first_name">First Name:</label>
        <input name="first_name" id="first_name" type="text" value="<?= $firstName?>">
    </div>
    <div>
        <label for="last_name">Last Name:</label>
        <input name="last_name" id="last_name" type="text" value="<?= $lastName?>">
    </div>
    <div>
        <label for="middle_name">Middle Name:</label>
        <input name="middle_name" id="middle_name" type="text" value="<?= $middleName?>">
    </div>
    <div>
        <label for="gender">Gender:</label>
        <input name="gender" id="gender" type="text" value="<?= $gender?>">
    </div>
    <div>
        <label for="birth_date">Birth Date:</label>
        <input name="birth_date" id="birth_date" type="date" value="<?= $birthDate?>">
    </div>
    <div>
        <label for="email">Email:</label>
        <input name="email" id="email" type="text" value="<?= $email?>">
    </div>
    <div>
        <label for="phone">Phone:</label>
        <input name="phone" id="phone" type="text" value="<?= $phone?>">
    </div>
    <div>
        <label for="avatar_path">Avatar Path:</label>
        <input name="avatar_path" id="avatar_path" type="file" accept="image/png, image/jpeg, image/gif" value="$avatarPath">
    </div>
    <button type="submit">Update</button>