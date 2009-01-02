<?php

/**
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                                                                         *
 *  /////////////////////////////////////////////////////////////////////////////////////  *
 *  //                                                                                 //  *
 *  //    checkip.php - Last modified 01. 01 2009                                      //  *
 *  //                                                                                 //  *
 *  //    Copyright (c) 2008 Gunnar Guðvarðarson                                       //  *
 *  //                                                                                 //  *
 *  //  Permission is hereby granted, free of charge, to any person obtaining a copy   //  *
 *  //  of this software and associated documentation files (the "Software"), to deal  //  *
 *  //  in the Software without restriction, including without limitation the rights   //  *
 *  //  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell      //  *
 *  //  copies of the Software, and to permit persons to whom the Software is          //  *
 *  //  furnished to do so, subject to the following conditions:                       //  *
 *  //                                                                                 //  *
 *  //  The above copyright notice and this permission notice shall be included in     //  *
 *  //  all copies or substantial portions of the Software.                            //  *
 *  //                                                                                 //  *
 *  //  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR     //  *
 *  //  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,       //  *
 *  //  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE    //  *
 *  //  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER         //  *
 *  //  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,  //  *
 *  //  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN      //  *
 *  //  THE SOFTWARE.                                                                  //  *
 *  //                                                                                 //  *
 *  /////////////////////////////////////////////////////////////////////////////////////  *
 *                                                                                         *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
**/

// prevents this script from being run directly
// the variable $run must be set for the script to run
// it should only be set in the file that includes this one !
if (!isset($run))
{ return; }

// NOTE: this function has NO caching.
function ipicelandic($ip)
{
    $parts = @explode(".", $ip, 4);
    $parts = @array_reverse($parts);
    return @checkdnsrr(implode(".", $parts) . ".iceland.rix.is", "A");
}

ipicelandic($_SERVER['REMOTE_ADDR']);

?>
