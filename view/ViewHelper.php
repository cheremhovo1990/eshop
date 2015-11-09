<?php
namespace eshop\view;

//require_once( "woo/base/Registry.php" );

class ViewHelper {
    static function getRequest() {
        return \eshop\base\ApplicationRegistry::getRequest();
    }

    public static function redirect_invalid_user($check = 'user_id', $destination = 'index.php', $protocol = 'http://')
    {
        // Check for the session item:
        if (!isset($_SESSION[$check])) {
            $url = $protocol . BASE_URL . $destination; // Define the URL.
            header("Location: $url");
            exit(); // Quit the script.
        }
    }

    public static function create_form_input($name, $type, $label = '', $errors = array(), $options = array())
    {
        // Assume no value already exists:
        $value = false;

        // Check for a value in POST:
        if (isset($_POST[$name])) $value = $_POST[$name];

        // Start the DIV:
        echo '<div class="form-group';

        // Add a class if an error exists:
        if (array_key_exists($name, $errors)) echo ' has-error';

        // Complete the DIV:
        echo '">';

        // Create the LABEL, if one was provided:
        if (!empty($label)) echo '<label for="' . $name . '" class="control-label">' . $label . '</label>';

        // Conditional to determine what kind of element to create:
        if ( ($type === 'text') || ($type === 'password') || ($type === 'email')) {

            // Start creating the input:
            echo '<input type="' . $type . '" name="' . $name . '" id="' . $name . '" class="form-control"';

            // Add the value to the input:
            if ($value) echo ' value="' . htmlspecialchars($value) . '"';

            // Check for additional options:
            if (!empty($options) && is_array($options)) {
                foreach ($options as $k => $v) {
                    echo " $k=\"$v\"";
                }
            }

            // Complete the element:
            echo '>';

            // Show the error message, if one exists:
            if (array_key_exists($name, $errors)) echo '<span class="help-block">' . $errors[$name] . '</span>';

        } elseif ($type === 'textarea') { // Create a TEXTAREA.

            // Show the error message above the textarea (if one exists):
            if (array_key_exists($name, $errors)) echo '<span class="help-block">' . $errors[$name] . '</span>';

            // Start creating the textarea:
            echo '<textarea name="' . $name . '" id="' . $name . '" class="form-control"';

            // Check for additional options:
            if (!empty($options) && is_array($options)) {
                foreach ($options as $k => $v) {
                    echo " $k=\"$v\"";
                }
            }

            // Complete the opening tag:
            echo '>';

            // Add the value to the textarea:
            if ($value) echo $value;

            // Complete the textarea:
            echo '</textarea>';

        } // End of primary IF-ELSE.

        // Complete the DIV:
        echo '</div>';
    }
}

?>
