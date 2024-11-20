<?php

/*
Search function. Takes in a term to search for and and a table and searches all entries in the table for the search term.
*/
function search($search, $table) {
    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER,
            DBPASS
        );

        //Set the encryption mode. I don't set the key string and init vector because I have them as a constant
        $set_encryption_mode_query = "SET block_encryption_mode = 'aes-256-cbc';";
        $statement = $db -> prepare($set_encryption_mode_query);
        $statement -> execute();
        $statement = null;

        //We want to search for users who have registered at a website
        if ("Full Entries" === $table) {
            $select_query = "SELECT user_id, first_name, last_name, site_id, site_name, url, username, CAST(AES_DECRYPT(password, " . KEY_STR .", " . INIT_VECTOR . ") AS CHAR) AS 'password', email_address, comment, time_stamp FROM registers_at JOIN users USING (user_id) JOIN websites USING (site_id) WHERE site_id LIKE '%{$search}%' OR user_id LIKE '%{$search}%' OR first_name LIKE '%{$search}%' OR last_name LIKE '%{$search}%' OR username LIKE '%{$search}%' OR password LIKE '%{$search}%' OR email_address LIKE '%{$search}%' OR comment LIKE '%{$search}%' OR time_stamp LIKE '%{$search}%' OR site_name LIKE '%{$search}%' OR url LIKE '%{$search}%';";
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
                    echo "            <td>" . htmlspecialchars($row[7]) . "</td>\n";
                    echo "            <td>" . htmlspecialchars($row[8]) . "</td>\n";
                    echo "            <td>" . htmlspecialchars($row[9] == null ? "" : $row[9]) . "</td>\n"; //do not pass null comments, which htmlspecialchars does not allow
                    echo "            <td>" . htmlspecialchars($row[10]) . "</td>\n";
                    echo "          </tr>\n";
                }
                echo "         </tbody>\n";
                echo "      </table>\n";
            }
        }
        else if ("Users" === $table) { //Search only users table
            $select_query = "SELECT first_name, last_name, user_id FROM users WHERE user_id LIKE '%{$search}%' OR first_name LIKE '%{$search}%' OR last_name LIKE '%{$search}%';";
            $statement = $db -> prepare($select_query);
            $statement -> execute();

            if (count($statement -> fetchAll()) == 0) {
                return 0;
            } else {
                echo "      <table>\n";
                echo "        <thead>\n";
                echo "          <tr>\n";
                echo "            <th>User First Name</th>\n";
                echo "            <th>Last Name</th>\n";
                echo "            <th>User ID</th>\n";
                echo "          </tr>\n";
                echo "        </thead>\n";
                echo "        <tbody>\n";

                // Populate the table with data coming from the database...
                foreach ($db ->query($select_query) as $row) {
                    echo "          <tr>\n";
                    echo "            <td>" . htmlspecialchars($row[0]) . "</td>\n";
                    echo "            <td>" . htmlspecialchars($row[1]) . "</td>\n";
                    echo "            <td>" . htmlspecialchars($row[2]) . "</td>\n";
                    echo "          </tr>\n";
                }
                echo "         </tbody>\n";
                echo "      </table>\n";
            }
            $statement = null;
        }
        else if ("Websites" === $table) { //Search websites table
            $select_query = "SELECT site_name, url, site_id FROM websites WHERE site_id LIKE '%{$search}%' OR site_name LIKE '%{$search}%' OR url LIKE '%{$search}%';";
            $statement = $db -> prepare($select_query);
            $statement -> execute();

            if (count($statement -> fetchAll()) == 0) {
                return 0;
            } else {
                echo "      <table>\n";
                echo "        <thead>\n";
                echo "          <tr>\n";
                echo "            <th>Website Name</th>\n";
                echo "            <th>URL</th>\n";
                echo "            <th>Website ID</th>\n";
                echo "          </tr>\n";
                echo "        </thead>\n";
                echo "        <tbody>\n";

                // Populate the table with data coming from the database...
                foreach ($db ->query($select_query) as $row) {
                    echo "          <tr>\n";
                    echo "            <td>" . htmlspecialchars($row[0]) . "</td>\n";
                    echo "            <td>" . htmlspecialchars($row[1]) . "</td>\n";
                    echo "            <td>" . htmlspecialchars($row[2]) . "</td>\n";
                    echo "          </tr>\n";
                }
                echo "         </tbody>\n";
                echo "      </table>\n";
            }
        }
        else if ("Accounts" === $table) { //Search only accounts table
            $select_query = "SELECT user_id, site_id, username, CAST(AES_DECRYPT(password, " . KEY_STR .", " . INIT_VECTOR . ") AS CHAR) AS 'password', email_address, comment, time_stamp FROM registers_at WHERE site_id LIKE '%{$search}%' OR user_id LIKE '%{$search}%' OR username LIKE '%{$search}%' OR password LIKE '%{$search}%' OR email_address LIKE '%{$search}%' OR comment LIKE '%{$search}%' OR time_stamp LIKE '%{$search}%';";
            $statement = $db -> prepare($select_query);
            $statement -> execute();

            if (count($statement -> fetchAll()) == 0) {
                return 0;
            } else {
                echo "      <table>\n";
                echo "        <thead>\n";
                echo "          <tr>\n";
                echo "            <th>User ID</th>\n";
                echo "            <th>Website ID</th>\n";
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
                    echo "            <td>" . htmlspecialchars($row[5] == null ? "" : $row[5]) . "</td>\n"; //do not pass null comments, which htmlspecialchars does not allow
                    echo "            <td>" . htmlspecialchars($row[6]) . "</td>\n";
                    echo "          </tr>\n";
                }
                echo "         </tbody>\n";
                echo "      </table>\n";
            }
        }

        $statement = null;
    } catch( PDOException $e ) {
        echo '<p>The following message was generated by function <code>search</code>:</p>' . "\n";
        echo '<p id="error">' . $e -> getMessage() . '</p>' . "\n";
        echo "<p>There are a few reasons for this. Perhaps the database doesn’t exist or wasn’t mounted. Does the volume/drive containing the database have read and write permissions?</p>\n";
        echo '<p>Click <a href="./">here</a> to go back.</p>';

        exit;
    }
}

/*
Update function. Updates the attribute in the correct table based on another attribute in the same or different table
*/
function update($update_attribute, $new_value, $query_attribute, $pattern) {
    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER,
            DBPASS
        );

        $set_encryption_mode_query = "SET block_encryption_mode = 'aes-256-cbc';";
        $statement = $db -> prepare($set_encryption_mode_query);
        $statement -> execute();
        $statement = null;

        //We want to make setting passwords easy, so automatically encrypt and decrypt
        if ("password" === $update_attribute) {
            $new_value = "AES_ENCRYPT(\"$new_value\", " . KEY_STR .", " . INIT_VECTOR . ")";
        }
        else if ("site_id" === $update_attribute) {
            $update_attribute = "websites.site_id"; //We must clarify not to use registers_at for the IDs or else SQL will say it is ambiguous (registers_at will update anyway due to cascading foreign keys)
            $new_value = "\"{$new_value}\""; //if we want to set a new password, we can't have " around the AES_ENCRYPT function, so we must add it for every other situation
        }
        else if ("user_id" === $update_attribute) {
            $update_attribute = "users.user_id";
            $new_value = "\"{$new_value}\"";
        }
        else {
            $new_value = "\"{$new_value}\"";
        }

        //Same as update attribute. Make querying off of passwords easy.
        if ("password" === $query_attribute) {
            $query_attribute = "CAST(AES_DECRYPT(password, " . KEY_STR .", " . INIT_VECTOR . ") AS CHAR)";
        }
        if ("site_id" === $query_attribute) {
            $query_attribute = "websites.site_id"; //still need to clarify when we use IDs
        }
        if ("user_id" === $query_attribute) {
            $query_attribute = "users.user_id";
        }

        /*The next if statements are needed because if we just join all tables together,
        users and websites which have no associated accounts are left out, meaning we otherwise could not update them.
        To solve this, we really only need to update using more than one table if the query attribute is from a different table than the update attribute
        */

        //If we use more than one table's attributes, we need to use all tables to update this attribute. This query does not include users that do not have an account or websites that have no accounts registered at it.
        $query_string = "UPDATE users, websites, registers_at SET {$update_attribute} = {$new_value} WHERE {$query_attribute}=\"{$pattern}\" AND users.user_id = registers_at.user_id AND websites.site_id = registers_at.site_id;";

        //If we are just using users attributes, we only update users, which includes users that do not have accounts associated with it.
        if (("first_name" === $update_attribute || "last_name" === $update_attribute || "users.user_id" === $update_attribute) && ("first_name" === $query_attribute || "last_name" === $query_attribute || "users.user_id" === $query_attribute)) {
            $query_string = "UPDATE users SET {$update_attribute} = {$new_value} WHERE {$query_attribute}=\"{$pattern}\";";
        }
        //If we are just using website attributes, only update websites, which includes websites that do not have websites associated with it
        else if (("site_name" === $update_attribute || "url" === $update_attribute || "websites.site_id" === $update_attribute) && ("site_name" === $query_attribute || "url" === $query_attribute || "websites.site_id" === $query_attribute)) {
            $query_string = "UPDATE websites SET {$update_attribute} = {$new_value} WHERE {$query_attribute}=\"{$pattern}\";";
        }

        $statement = $db -> prepare($query_string);
        $statement -> execute();

        $count = $statement -> rowCount();
        $statement = null;
        echo "<h2>Entries Affected: {$count}</h2>";
    } catch( PDOException $e ) {
        echo '<p>The following message was generated by function <code>update</code>:</p>' . "\n";
        echo '<p id="error">' . $e -> getMessage() . '</p>' . "\n";

        exit;
    }
}

function insert_user($first_name, $last_name) {
    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER,
            DBPASS
        );

        $statement = $db -> prepare("INSERT INTO users (first_name, last_name) VALUES (\"{$first_name}\", \"{$last_name}\");");
        $statement -> execute();
        $statement = null;

    } catch(PDOException $e) {
        echo '<p>The following message was generated by function <code>insert</code>:</p>' . "\n";
        echo '<p id="error">' . $e -> getMessage() . '</p>' . "\n";
        exit;
    }
}

function insert_website($website_name, $url) {
    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER,
            DBPASS
        );

        $statement = $db -> prepare("INSERT INTO websites (site_name, url) VALUES (\"{$website_name}\", \"{$url}\");");
        $statement -> execute();
        $statement = null;

    } catch(PDOException $e) {
        echo '<p>The following message was generated by function <code>insert</code>:</p>' . "\n";
        echo '<p id="error">' . $e -> getMessage() . '</p>' . "\n";
        exit;
    }
}

function insert_account($site_id, $user_id, $username, $password, $email_address, $comment) {
    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER,
            DBPASS
        );

        $set_encryption_mode_query = "SET block_encryption_mode = 'aes-256-cbc';";
        $statement = $db -> prepare($set_encryption_mode_query);
        $statement -> execute();

        $statement = $db -> prepare("INSERT INTO registers_at (username, password, email_address, user_id, site_id, comment) VALUES (\"{$username}\", AES_ENCRYPT(\"{$password}\", " . KEY_STR .", " . INIT_VECTOR . "), \"{$email_address}\", \"{$user_id}\", \"{$site_id}\", \"{$comment}\");");
        $statement -> execute();
        $statement = null;

    } catch(PDOException $e) {
        echo '<p>The following message was generated by function <code>insert</code>:</p>' . "\n";
        echo '<p id="error">' . $e -> getMessage() . '</p>' . "\n";
        exit;
    }
}

function delete($table, $query_attribute, $pattern) {
    try {
        $db = new PDO(
            "mysql:host=" . DBHOST . "; dbname=" . DBNAME . ";charset=utf8",
            DBUSER,
            DBPASS
        );

        $set_encryption_mode_query = "SET block_encryption_mode = 'aes-256-cbc';";
        $statement = $db -> prepare($set_encryption_mode_query);
        $statement -> execute();
        $statement = null;

        //we display accounts to the user as an option, but the actual table is registers_at
        if ("accounts" === $table) {
            $table = "registers_at";
        }

        //Make it easy for the user to delete an account if they know the password
        if ("password" === $query_attribute) {
            $query_attribute = "CAST(AES_DECRYPT(password, " . KEY_STR .", " . INIT_VECTOR . ") AS CHAR)";
        }
        if ("site_id" === $query_attribute) {
            $query_attribute = "websites.site_id"; //we show the user the option for site_id but we want to use website.site_id to not be ambiguous with registers_at.site_id
        }
        if ("user_id" === $query_attribute) {
            $query_attribute = "users.user_id"; //similar problem with website.site_id
        }

        //Assume we are deleting using all of the tables.
        //If we just want to delete a user using user attributes, this would not delete users who do not have an account, because in the JOIN they become a dangling tuple
        //The same is true for websites which do not have any associated accounts
        $query_string = "DELETE FROM {$table} USING users INNER JOIN registers_at INNER JOIN websites WHERE {$query_attribute} = \"{$pattern}\" AND users.user_id = registers_at.user_id AND websites.site_id = registers_at.site_id;";

        //If we are deleting from the users table and just using users attributes, we don't need to join with the other tables. Allows for deletion of users who do not have any accounts at websites
        if ("users" === $table && ("first_name" === $query_attribute || "last_name" === $query_attribute || "user_id" === $query_attribute)) {
            $query_string = "DELETE FROM {$table} WHERE {$query_attribute} = \"{$pattern}\";";
        }
        else if ("websites" === $table && ("site_name" === $query_attribute || "url" === $query_attribute || "site_id" === $query_attribute)) {
            $query_string = "DELETE FROM {$table} WHERE {$query_attribute} = \"{$pattern}\";";
        }

        $db -> query($query_string);
    } catch( PDOException $e ) {
        echo '<p>The following message was generated by function <code>update</code>:</p>' . "\n";
        echo '<p id="error">' . $e -> getMessage() . '</p>' . "\n";

        exit;
    }
}
