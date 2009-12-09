<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {dhtml_calendar} function plugin
 *
 * Type:     function<br>
 * Name:     dhtml_calendar<br>
 * Purpose:  interface to jscalendar-1.0
 * Author:   boots
 * Version:  1.1.4
 * @link http://www.dynarch.com/projects/calendar/ {dhtml calendar}
 *          (dynarch.com)
 * @param array
 * @param Smarty
 * @return string
 */

/*

Following is the complete list of properties interpreted by Calendar.setup.
All of them have default values, so you can pass only those which you would like
to customize.

Required: popup (inputField, displayArea, button), flat (flat)

inputField string null
    The ID of your input field.

displayArea string null
    This is the ID of a <span>, <div>, or any other element that you would like
    to use to display the current date. This is generally useful only if the
    input field is hidden, as an area to display the date.

button string null
    The ID of the calendar "trigger&#65533;. This is an element (ordinarily a button or
    an image) that will dispatch a certain event (usually "click") to the
    function that creates and displays the calendar.

eventName string "click"
    The name of the event that will trigger the calendar. The name should be
    without the "on" prefix, such as "click" instead of "onclick". Virtually
    all users will want to let this have the default value ("click"). Anyway,
    it could be useful if, say, you want the calendar to appear when the input
    field is focused and have no trigger button (in this case use "focus" as the
    event name).

ifFormat string "%Y/%m/%d"
    The format string that will be used to enter the date in the input field.
    This format will be honored even if the input field is hidden.

daFormat string "%Y/%m/%d"
    Format of the date displayed in the displayArea (if specified).

singleClick boolean true
    Wether the calendar is in "single-click mode" or "double-click mode". If
    true (the default) the calendar will be created in single-click mode.

disableFunc function null
    A function that receives a JS Date object. It should return true if that
    date has to be disabled, false otherwise. DEPRECATED (see below).

dateStatusFunc function null
    A function that receives a JS Date object and returns a boolean or a string.
    This function allows one to set a certain CSS class to some date, therefore
    making it look different. If it returns true then the date will be disabled.
    If it returns false nothing special happens with the given date. If it
    returns a string then that will be taken as a CSS class and appended to the
    date element. If this string is "disabled" then the date is also disabled
    (therefore is like returning true). For more information please also refer
    to section 4.3.8.

firstDay integer 0
    Specifies which day is to be displayed as the first day of week. Possible
    values are 0 to 6; 0 means Sunday, 1 means Monday, ..., 6 means Saturday.
    The end user can easily change this too, by clicking on the day name in the
    calendar header.

weekNumbers boolean true
    If "true" then the calendar will display week numbers.

align string "Bl"
    Alignment of the calendar, relative to the reference element. The reference
    element is dynamically chosen like this: if a displayArea is specified then
    it will be the reference element. Otherwise, the input field is the
    reference element. For the meaning of the alignment characters please
    section 4.3.11.

range array [1900, 2999]
    An array having exactly 2 elements, integers. (!) The first [0] element is
    the minimum year that is available, and the second [1] element is the
    maximum year that the calendar will allow.

flat string null
    If you want a flat calendar, pass the ID of the parent object in this
    property. If not, pass null here (or nothing at all as null is the default
    value).

flatCallback function null
    You should provide this function if the calendar is flat. It will be called
    when the date in the calendar is changed with a reference to the calendar
    object. See section 2.2 for an example of how to setup a flat calendar.

onSelect function null
    If you provide a function handler here then you have to manage the
    "click-on-date" event by yourself. Look in the calendar-setup.js and take as
    an example the onSelect handler that you can see there.

onClose function null
    This handler will be called when the calendar needs to close. You don&#65533;t need
    to provide one, but if you do it&#65533;s your responsibility to hide/destroy the
    calendar. You&#65533;re on your own. Check the calendar-setup.js file for an
    example.

onUpdate function null
    If you supply a function handler here, it will be called right after the
    target field is updated with a new date. You can use this to chain 2
    calendars, for instance to setup a default date in the second just after a
    date was selected in the first.

date date null
    This allows you to setup an initial date where the calendar will be
    positioned to. If absent then the calendar will open to the today date.

showsTime boolean false
    If this is set to true then the calendar will also allow time selection.

timeFormat string "24"
    Set this to "12" or "24" to configure the way that the calendar will display
    time.

electric boolean true
    Set this to "false" if you want the calendar to update the field only when
    closed (by default it updates the field at each date change, even if the
    calendar is not closed)

position array null
    Specifies the [x, y] position, relative to page&#65533;s top-left corner, where the
    calendar will be displayed. If not passed then the position will be computed
    based on the "align" parameter. Defaults to "null" (not used).

cache boolean false
    Set this to "true" if you want to cache the calendar object. This means that
    a single calendar object will be used for all fields that require a popup
    calendar

showOthers boolean false
    If set to "true" then days belonging to months overlapping with the
    currently displayed month will also be displayed in the calendar (but in a
    "faded-out" color)

*/
function smarty_function_dhtml_calendar($params, $smarty)
{
    static $requires = array(
      'flat'            => 'string'
    , 'inputField'      => 'string'
    , 'displayArea'     => 'string'
    , 'button'          => 'string'
    );
    static $allowed = array(
      'date'            => 'date'

    , 'eventName'       => 'string'
    , 'cache'           => 'boolean'

    , 'multiple'        => 'boolean'
    , 'singleClick'     => 'boolean'
    , 'electric'        => 'boolean'
    , 'position'        => 'array'
    , 'align'           => 'string'
    , 'firstDay'        => 'integer'
    , 'weekNumbers'     => 'boolean'
    , 'range'           => 'array'
    , 'showOthers'      => 'boolean'
    , 'showsTime'       => 'boolean'

    , 'timeFormat'      => 'string'
    , 'ifFormat'        => 'string'
    , 'daFormat'        => 'string'

    , 'flatCallback'    => 'function'
    , 'dateStatusFunc'  => 'function'
    , 'dateText'        => 'function'

    , 'onSelect'        => 'function'
    , 'onClose'         => 'function'
    , 'onUpdate'        => 'function'
    );
    static $deprecated = array(
      'disableFunc'     => 'function'
    );
    static $all = false;

    if( !$all ) {
        $all = array_merge( $requires, $allowed, $deprecated );
    }
    $_params = array();

    foreach( $requires as $field=>$type ) {

        if( array_key_exists( $field, $params )
            && !empty( $params[$field] )
        ) {
            $_params[$field] = $params[$field];
        }
    }

    if( empty( $_params ) ) {
        $smarty->trigger_error( '[dhtml_calendar] missing required parameter(s)', E_USER_ERROR );

        return( false );
    }

    foreach( $allowed as $field=>$type ) {

        if( array_key_exists( $field, $params )
            && isset( $params[$field] )
        ) {
            $_params[$field] = $params[$field];
        }
    }

    foreach( $deprecated as $field=>$type ) {

        if( array_key_exists( $field, $params ) ) {
            $smarty->trigger_error( '[dhtml_calendar] deprecated parameter "' . $field . '"', E_USER_INFO );
            $_params[$field] = $params[$field];
        }
    }
    $_params_out = '';

    foreach ($_params as $field=>$value) {

        if( is_bool( $value ) ) {
            $value = ( $value )
                ? 'true'
                : 'false';

        } elseif( !is_numeric( $value )
            && ( $all[$field] == 'array' )
        ) {
            if( is_string( $value ) ) {
                $value = explode( ',', $value );
            }

            if( is_array( $value ) ) {
                $_values = '';
                $val_count = count( $value );

                for( $i=0; $i < $val_count; $i++ ) {
                    $_values .= $value[$i];

                    if( ( $i + 1 ) != $val_count ) {
                        $_values .= ',';
                    }
                }
                $value = '[' . $_values . ']';
            }

        } elseif( !is_numeric( $value )
            && !( $all[$field] == 'function' )
        ) {
            $value = '"' . $value . '"';
        }

        if( $_params_out ) {
            $_params_out .= ',';
        }
        $_params_out .= '"' . $field . '":' . $value;
    }

    return(
        "\n<script type=\"text/javascript\">\n// <![CDATA[\n"
        .'Calendar.setup({'
        . $_params_out
        . '});'
        . "\n// ]]>\n</script>\n"
    );
}