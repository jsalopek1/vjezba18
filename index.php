<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>User edits</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <div class="container">
        <h1 class="title">User update information</h1>

        <?php
        $MySQL = mysqli_connect("localhost", "root", "", "vjezba18");
        if (!$MySQL) {
            die("<div class='alert error'>Error: Unable to connect to the database.</div>");
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['user_id'])) {
            $user_id = intval($_POST['user_id']);
            $user_firstname = mysqli_real_escape_string($MySQL, $_POST['user_firstname']);
            $user_lastname = mysqli_real_escape_string($MySQL, $_POST['user_lastname']);
            $country_code = mysqli_real_escape_string($MySQL, $_POST['country_code']);

            $update_query = "UPDATE users 
                             SET user_firstname = '$user_firstname', 
                                 user_lastname = '$user_lastname', 
                                 country_code = '$country_code' 
                             WHERE id = $user_id";

            if (mysqli_query($MySQL, $update_query)) {
                echo "<div class='alert success'>User with ID $user_id updated successfully.</div>";
            } else {
                echo "<div class='alert error'>Error: " . mysqli_error($MySQL) . "</div>";
            }
        }

        $query = "SELECT users.id, users.user_firstname, users.user_lastname, users.country_code, country.country_name
                  FROM users
                  LEFT JOIN country ON users.country_code = country.country_code";
        $result = mysqli_query($MySQL, $query);

        if (!$result || mysqli_num_rows($result) === 0) {
            echo "<div class='alert warning'>No users found.</div>";
        } else {
            echo "<table class='user-table'>";
            echo "<thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Country</th>
                        <th>Save</th>
                    </tr>
                  </thead>";
            echo "<tbody>";

            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <tr>
                    <form method="POST" action="">
                        <td>
                            <input type="text" name="user_firstname" value="<?= htmlspecialchars($row['user_firstname']); ?>" required>
                        </td>
                        <td>
                            <input type="text" name="user_lastname" value="<?= htmlspecialchars($row['user_lastname']); ?>" required>
                        </td>
                        <td>
                            <select name="country_code" required>
                                <?php
                                $country_query = "SELECT country_code, country_name FROM country";
                                $country_result = mysqli_query($MySQL, $country_query);
                                while ($country = mysqli_fetch_assoc($country_result)) {
                                    $selected = ($country['country_code'] === $row['country_code']) ? "selected" : "";
                                    echo "<option value='" . $country['country_code'] . "' $selected>" . htmlspecialchars($country['country_name']) . "</option>";
                                }
                                ?>
                            </select>
                        </td>
                        <td>
                            <input type="hidden" name="user_id" value="<?= $row['id']; ?>">
                            <button type="submit" class="btn">Save</button>
                        </td>
                    </form>
                </tr>
                <?php
            }

            echo "</tbody></table>";
        }

        mysqli_close($MySQL);
        ?>
    </div>

    <!-- vježba 18 - Azuriranje korisnika -->

</body>
</html>
