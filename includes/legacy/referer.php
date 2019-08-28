<?PHP
// File: $Id: referer.php 19273 2006-06-22 19:05:48Z markwest $
/*******************************************************************************
 * This is a new referer function for PostNuke Instead of logging each URL
 * as its coming in, it logs the frequency of that URL. This function was written
 * first by Michael Yarbrough [gte649i@prism.gatech.edu]. Bjorn Sodergren re-wrote
 * it to what it is now and added more complete/descriptive comments.
 *
 * modified from PHP-Nuke 4.4 to Postnuke .6* by
 * Timothy Litwiller [timlitw@onemain.com]
 *
 * Re-Written by
 * Bjorn Sodergren [sweede@gallatinriver.net]
 *
 * Originally written by
 * Michael Yarbrough [gte649i@prism.gatech.edu]
 *
 ******************************************************************************/

function httpreferer()
{
    /***
     * Here we set up some variables for the rest of the script.
     * if you want to see whats going on, set $DEBUG to 1
     * I use $httphost here because i dont want to deal with the need to have
     * to see if $nuke_url is set correctly and whatnot. if you prefer to use
     * $nuke_url isntead of HTTP_HOST, just uncomment the appropriate lines.
     */
    $DEBUG = 0;
    $httpreferer = pnServerGetVar('HTTP_REFERER');
    $httphost = pnServerGetVar('HTTP_HOST');

    $dbconn =& pnDBGetConn(true);
    $pntable =& pnDBGetTables();

    if($DEBUG == 1) {
        echo 'HTTP_HOST = '.pnVarPrepForDisplay($httphost).'<br /> HTTP_REFERER = '.pnVarPrepForDisplay($httpreferer).'<br />';
    }

    /***
     * This is the first thing we need to check. what this does is see if
     * HTTP_HOST is anywhere in HTTP_REFERER. This is so we dont log hits coming
     * from our own domain.
     */
    if (!ereg("$httphost",$httpreferer)) {
        /***
         * If $httpreferer is not set, set $httpreferer to value "bookmark"
         * This is to show how many people have this bookmarked or type in the
         * URL into the browser. also so we dont have empty referers.
         */
        if ( $httpreferer == '' ) {
            $httpreferer = 'bookmark';
        }
        $httpreferer = trim($httpreferer);
        $writeref = true;
        $refex = pnConfigGetVar('httprefexcluded');
        if (!empty($refex)) {
            $refexclusion = explode(' ', $refex);
            $count = count($refexclusion);
            $eregicondition = "((";
            for ($i = 0; $i < $count; $i++) {
                if ($i != $count-1) {
                    $eregicondition .= $refexclusion[$i] . ")|(";
                } else {
                    $eregicondition .= $refexclusion[$i] . "))";
                }
            }

            if (eregi($eregicondition, $httpreferer)) {
                $writeref = false;
            }
        }

      if ($writeref == true) {
        // grab a reference to our table column defs for easier reading below
        $column = &$pntable['referer_column'];

        /***
         * Lets select from the table where we have $httpreferer (whether it be
         * a valid referer or 'bookmark'. if we return 1 row, that means someones
         * used this referer before and update the set appropriatly.
         *
         * If we dont have any rows (it returns 0), we have a new entry in the
         * table, update accordingly.
         *
         * After we figure out what SQL statement we are using, lets perform the
         * query and we're done !
         */
        $check_sql = "SELECT count($column[rid]) as c
                      FROM $pntable[referer]
                      WHERE $column[url] = '" . pnVarPrepForStore($httpreferer) . "'";
        $result =& $dbconn->Execute($check_sql);
        if ($dbconn->ErrorNo() != 0) {
          pnSessionSetVar('errormsg', 'Error: ' . $dbconn->ErrorNo() . ': ' . $dbconn->ErrorMsg());
          return false;
        }
        $row = $result->fields;
        $count = $row[0];

        if ($count == 1 ) {
            $update_sql = "UPDATE $pntable[referer]
                           SET $column[frequency] = $column[frequency] + 1
                           WHERE $column[url] = '" . pnVarPrepForStore($httpreferer) . "'";
        } else {
            /***
             * "auto-increment" isn't portable so we have to use the standard
             * interface for grabbing sequence numbers.  The underlying
             * implementation handles the correct method for the RDBMS we are
             * using.
             */
            $rid = $dbconn->GenId($pntable['referer'], true);
            $update_sql = "INSERT INTO $pntable[referer]
                             ($column[rid],
                              $column[url],
                              $column[frequency])
                           VALUES
                             (" . pnVarPrepForStore($rid). ",
                              '" . pnVarPrepForStore($httpreferer) . "',
                              1)";
        }

        $result =& $dbconn->Execute($update_sql);
        if ($dbconn->ErrorNo() != 0) {
          pnSessionSetVar('errormsg', 'Error: ' . $dbconn->ErrorNo() . ': ' . $dbconn->ErrorMsg());
          return false;
        }

        if ( $DEBUG == 1) {
            echo "<br />".$check_sql."<br />".$update_sql."<br />";

        }
      }
    }
}
?>