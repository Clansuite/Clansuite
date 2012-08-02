<?php

/**
 * Clansuite - just an eSports CMS
 * Jens-André Koch © 2005 - onwards
 * http://www.clansuite.com/
 *
 * This file is part of "Clansuite - just an eSports CMS".
 *
 * License: GNU/GPL v2 or any later version, see LICENSE file.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace Clansuite\Installation\Steps;

/**
 * Step 6 - Create Administrator Account
 */
class Step6 extends \Clansuite\Installation\Page
{
    public function getDefaultValues()
    {
        $values = array();

        $values['admin_name']     = isset($_SESSION['admin_name'])     ? $_SESSION['admin_name']     : 'admin';
        $values['admin_password'] = isset($_SESSION['admin_password']) ? $_SESSION['admin_password'] : 'admin';
        $values['admin_email']    = isset($_SESSION['admin_email'])    ? $_SESSION['admin_email']    : 'admin@email.com';
        $values['admin_language'] = isset($_SESSION['admin_language']) ? $_SESSION['admin_language'] : 'en_EN';

        return $values;
    }

    public function validateFormValues()
    {
        $error = '';

        if (isset($_POST['admin_name']) and isset($_POST['admin_password'])) {
            if (!ctype_alnum($_POST['admin_name'])) {
                $error .= '<p>The admin username might only contain alphanumeric characters.';
            }

            if ($error != '') {
               $this->setErrorMessage($error);

                // Values are not valid.
               return false;
            }

            // Values are valid.
            return true;
        } else {
            $error = $this->language['STEP6_ERROR_COULD_NOT_CREATE_ADMIN'];

            $this->setErrorMessage($error);

            // Values are not valid.
            return false;
        }
    }

    public function processValues()
    {
        /**
         * security class is required
         * for building the user password and salt hashes.
         */
        require ROOT . 'core/security.php';

        // generate salted hash
        $hashArray = \Koch\Security::build_salted_hash(
                        $_POST['admin_password'], $_SESSION['encryption']
        );

        /**
         * Insert admin user into the database.
         *
         * We are using a raw sql statement with bound variables passing it to Doctrine2.
         */
        try {
            $db = \Clansuite\Installation_Helper::getDoctrineEntityManager()->getConnection();

            $raw_sql_query = 'INSERT INTO ' . $_SESSION['config']['database']['prefix'] . 'users
                            SET  email = :email,
                                nick = :nick,
                                passwordhash = :hash,
                                salt = :salt,
                                joined = :joined,
                                language = :language,
                                activated = :activated';

            $stmt = $db->prepare($raw_sql_query);

            $params = array(
                'email' => $_POST['admin_email'],
                'nick' => $_POST['admin_name'],
                'hash' => $hashArray['hash'],
                'salt' => $hashArray['salt'],
                'joined' => time(),
                'language' => $_SESSION['admin_language'],
                'activated' => '1'
            );

            $stmt->execute($params);
        } catch (\Exception $e) {
            $this->setStep(6);
            $this->setErrormessage($e->getMessage());
        }
    }

}
