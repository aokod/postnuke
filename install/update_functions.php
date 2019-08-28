<?php 
// File: $Id: update_functions.php 15630 2005-02-04 06:35:42Z jorg $
// ----------------------------------------------------------------------
// PostNuke Content Management System
// Copyright (C) 2001 by the PostNuke Development Team.
// http://www.postnuke.com/
// ----------------------------------------------------------------------
// Based on:
// PHP-NUKE Web Portal System - http://phpnuke.org/
// Thatware - http://thatware.org/
// ----------------------------------------------------------------------
// LICENSE
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License (GPL)
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// To read the license please visit http://www.gnu.org/copyleft/gpl.html
// ----------------------------------------------------------------------
// Original Author of file:
// Purpose of file: Include the functions needed for the table validation
// ----------------------------------------------------------------------
/**
 * Function: CheckForField
 * Purpose: Returns true if field exists, false otherwise
 */
function CheckForField($field_name, $table_name)
{
    $result = mysql_query("desc $GLOBALS[prefix]_$table_name");
    while ($row = mysql_fetch_array($result)) {
        if ($row[Field] == $field_name) {
            return (true);
        } 
    } 

    return(false);
} 

/**
 * Function: GetFieldType
 * Purpose: Returns a string containing the datatype, false on error
 */

function GetFieldType($field_name, $table_name)
{
    $result = mysql_query("desc $GLOBALS[prefix]_$table_name");
    while ($row = mysql_fetch_array($result)) {
        if ($row[Field] == $field_name) {
            return ($row[Type]);
        } 
    } 
    return (false);
} 

/**
 * Function: CheckTableExists
 * Purpose: Returns true if the table exists.  False if not
 */

function CheckTableExists($table_name)
{
    $result = mysql_list_tables($GLOBALS[dbname]);
    while ($row = mysql_fetch_row($result)) {
        if ($row[0] == "$GLOBALS[prefix]_$table_name") {
            return true;
        } 
    } 
    return false;
} 

/**
 * Function: GetFields
 * Purpose: Returns an array of the fields
 */

function GetFields($table_name)
{
    $result = mysql_list_fields($GLOBALS[dbname], "$GLOBALS[prefix]_$table_name");
    $total = mysql_num_fields($result);
    for ($i = 0; $i < $total; $i++) {
        // name
        $field_name = mysql_field_name($result, $i); 
        // type & size
        $result2 = mysql_query("desc $GLOBALS[prefix]_$table_name");
        while ($row = mysql_fetch_array($result2)) {
            if ($row[Field] == $field_name) {
                $field_type = $row[Type];
                $field_default = $row['Default'];
            } 
        } 
        if (strstr($field_type, '(')) {
            $data = explode('(', $field_type);
            $data2 = explode(')', $data[1]);
            $field_type = $data[0];
            $field_size = $data2[0];
        } else {
            $field_size = mysql_field_len($result, $i);
        } 
        // flags
        $field_flags = '';
        $flags = explode(' ', mysql_field_flags($result, $i));
        foreach ($flags as $k => $v) { // just makes things easer :-)
            $field_flags[$v] = true;
        } 
        // put it all together
        $fields[$field_name] = array ('type' => $field_type,
            'def' => $field_default,
            'auto_increment' => $field_flags[auto_increment],
            'binary' => $field_flags[binary],
            'blob' => $field_flags[blob],
            'enum' => $field_flags[enum],
            'multiple_key' => $field_flags[multiple_key],
            'not_null' => $field_flags[not_null],
            'primary_key' => $field_flags[primary_key],
            'timestamp' => $field_flags[timestamp],
            'unique_key' => $field_flags[unique_key],
            'unsigned' => $field_flags[unsigned],
            'zerofill' => $field_flags[zero_fill]
            );
        switch ($field_type) { // need to add enum/set code someday
            case 'float':
            case 'decimal':
                $fsize = explode(',', $field_size);
                $fields[$field_name][size] = $fsize[0];
                $fields[$field_name][fraction] = $fsize[1];
                break;
            default:
                $fields[$field_name][size] = $field_size;
                break;
        } 
    } 
    return $fields;
} 

?>