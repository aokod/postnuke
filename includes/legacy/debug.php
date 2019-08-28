<?php

   class debug
   {
      var $status  = true;
      var $timer   = array();
      var $libel   = array();
      var $message = array();

      function debug($status=false)
      {
//         if ($status)
//          error_reporting (E_ALL);
//         else
//            error_reporting (E_ERROR | E_WARNING | E_PARSE);
         $this->status  = $status;
         $this->settime('Start');
      }

      function settime($libel)
      {
         list($micro,$time)  = explode(' ',microtime());
//       echo '<br />time = /'.$time.'/<br />';
 //      echo 'micro = /'.$micro.'/<br />';
         $this->timer[]  = (float) $time + (float) $micro;
         $this->libel[]  = "$libel";
      }

      function setmessage($proc,$line,$message)
      {
         $this->message[] = array("$proc","$line","$message");
      }

      function line($col1,$col2,$col3)
      {
         print("<tr>\n");
         print("<td valign='top'>$col1<td>\n");
         print("<td valign='top'>$col2<td>\n");
         print("<td valign='top'>$col3<td>\n");
         print("</tr>\n");
      }

      function report()
      {
         if ($this->status)
         {
            $this->settime('Page end');
            print('<h1>Debug report</h1>');
            print("<h2>Time report</h2>\n");
            print("<table border='1'>\n");
            $this->line('Step','Intermediate','Total');
            for ($i=1;$i<count($this->timer);$i++)
               $this->line($this->libel[$i],$this->timer[$i]-$this->timer[$i-1],$this->timer[$i]-$this->timer[0]);
            print("</table>\n");
            print("<h2>Messages report</h2>\n");
            print("<table border='1'>\n");
            $this->line('File','Line','Text');
            for ($i=0;$i<count($this->message);$i++)
               $this->line($this->message[$i][0],$this->message[$i][1],$this->message[$i][2]);
            print("</table>\n");
            print("</body>\n");
            print("</html>\n");
         }
      }
   }

   function debug_action($action,$parm1='',$parm2='',$parm3='')
   {
      static $debug;
      if ($action=='init')
         $debug = new debug($parm1);
      elseif ($action=='time')
         $debug->settime($parm1);
      elseif ($action=='message')
         $debug->setmessage($parm1,$parm2,$parm3);
      elseif ($action=='report')
         $debug->report();
   }

   function debug_time($libel='') {debug_action('time',$libel);}
   function debug_report()        {debug_action('report');}
   function debug_message($script,$line,$message)
                                  {debug_action('message',$script,$line,$message);}

   if (empty($debug)) {
       $debug = "false";
   }
   debug_action('init',$debug);
?>