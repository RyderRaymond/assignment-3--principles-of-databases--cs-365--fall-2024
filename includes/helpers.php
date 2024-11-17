<?php

function search($search) {
    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER,
            DBPASS
        );

        $set_encryption_mode_query = "SET block_encryption_mode = 'aes-256-cbc';";
        $statement = $db -> prepare($set_encryption_mode_query);
        $statement -> execute();

        $select_query = "SELECT user_id, first_name, last_name, site_id, site_name, url, username, CAST(AES_DECRYPT(password, " . KEY_STR .", " . INIT_VECTOR . ") AS CHAR) AS 'password', email_address, comment, time_stamp FROM registers_at JOIN users USING (user_id) JOIN websites USING (site_id) WHERE site_id LIKE '%$search%' OR user_id LIKE '%$search%' OR first_name LIKE '%$search%' OR last_name LIKE '%$search%' OR username LIKE '%$search%' OR password LIKE '%$search%' OR email_address LIKE '%$search%' OR comment LIKE '%$search%' OR time_stamp LIKE '%$search%' OR site_name LIKE '%$search%' OR url LIKE '%$search%'";
        $statement = $db -> prepare($select_query);
        $statement -> execute();

        if (count($statement -> fetchAll()) == 0) {
            return 0;
        } else {
            echo "      <table>\n";
            echo "        <thead>\n";
            echo "          <tr>\n";
            echo "            <th>User ID</th>\n";
            echo "            <th>User First Name</th>\n";
            echo "            <th>Last Name</th>\n";
            echo "            <th>Website ID</th>\n";
            echo "            <th>Website Name</th>\n";
            echo "            <th>URL</th>\n";
            echo "            <th>Username</th>\n";
            echo "            <th>Password</th>\n";
            echo "            <th>Email Address</th>\n";
            echo "            <th>Comment</th>\n";
            echo "            <th>Timestamp</th>\n";
            echo "          </tr>\n";
            echo "        </thead>\n";
            echo "        <tbody>\n";

            // Populate the table with data coming from the database...
            foreach ($db ->query($select_query) as $row) {
                echo "          <tr>\n";
                echo "            <td>" . htmlspecialchars($row[0]) . "</td>\n";
                echo "            <td>" . htmlspecialchars($row[1]) . "</td>\n";
                echo "            <td>" . htmlspecialchars($row[2]) . "</td>\n";
                echo "            <td>" . htmlspecialchars($row[3]) . "</td>\n";
                echo "            <td>" . htmlspecialchars($row[4]) . "</td>\n";
                echo "            <td>" . htmlspecialchars($row[5]) . "</td>\n";
                echo "            <td>" . htmlspecialchars($row[6]) . "</td>\n";
                echo "            <td>" . htmlspecialchars($row[7] == null ? "" : $row[7]) . "</td>\n"; //do not pass null comments, which htmlspecialchars does not allow
                echo "            <td>" . htmlspecialchars($row[8]) . "</td>\n";
                echo "            <td>" . htmlspecialchars($row[9]) . "</td>\n";
                echo "            <td>" . htmlspecialchars($row[10]) . "</td>\n";
                echo "          </tr>\n";
            }

            echo "         </tbody>\n";
            echo "      </table>\n";
        }
    } catch( PDOException $e ) {
        echo '<p>The following message was generated by function <code>search</code>:</p>' . "\n";
        echo '<p id="error">' . $e -> getMessage() . '</p>' . "\n";
        echo "<p>There are a few reasons for this. Perhaps the database doesn’t exist or wasn’t mounted. Does the volume/drive containing the database have read and write permissions?</p>\n";
        echo '<p>Click <a href="./">here</a> to go back.</p>';

        exit;
    }
}
