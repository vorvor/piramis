<?php
header('Content-Type: text/html; charset=utf-8');
?>
<html>
<head>
<style>
  h2 {
    margin:0;
  }
 .diamond {
   width:520px;
   height:60px;
   background:url('1.png') no-repeat;
   padding:18px 0;
   text-align:center;
   overflow:visible;
 }
 .diamond .cell {
  width:36px;
  position:absolute;
  left:8px;
 }
 .diamond .cell-color {
  width:10px;
  position:absolute;
  left:2px;
 }
 .diamond .color-picker {
  position: absolute;
  left:51px;
  top:13px;
  z-index:10;
  background: #fff;
  padding:6px;
  border:1px solid #444;
 }
 
 .color {
  width:20px;
  height:20px;
  background:red;
  float:left;
 }
 
  .color.color-purple {
    background:#ff00ff;
  }
  
  .color.color-orange {
    background:#ff9955;
  }
  
  .color.color-blue {
    background:#37abc8;
  }
  
  .color.color-reset {
    background:#f2f2f2;
  }
 
 
 
 #list_of_diamonds {
  position: absolute;
  top:10;
  right:10;
  background:#ccc;
  padding:10px;
 }
 #interface {
  margin:10px;
  background:#ccc;
  padding:10px;
  width:264px;
 }
 
 #interface label {
  display:block;
 }
 

 
</style>

</head>
<body>
<?php

class diamond{ 
 public $x,$y;
 public $width = 52;
 public $height = 60;
 public $origo = 500;
 public $output;
 public $data = array();


 
 public function add($x,$y){
  $xstart = -1*$y*($this->width/2);
 
  $xcord = $this->origo + $xstart + ($this->width-1) * $x;
  $ycord = ($this->height-16) * $y;
  
  $color = 1;
  if (isset($_GET['filename'])) {
   $color = $this->data[$x.'_'.$y.'_color'];
  }
  
  if (isset($_POST['cell_' . $x . '_'. $y . '_color'])) {
   $color = $_POST['cell_' . $x . '_'. $y . '_color'];
  }
  
  if ($color == '') {
    $color = 1;
  }
  
  
  //$this->output.= '<div class="diamond" style="position:absolute;left:' .$xcord . 'px;top:' . $ycord . 'px;">'.($x+1).':'.$y.'</div>';
  $this->output.= '<div class="diamond" style="position:absolute;left:' .$xcord . 'px;top:' . $ycord . 'px;background-image:url(' . $color . '.png);">';
  
  
  
  
  $colors = '';
  $colors.= '<div class="color color-red" color="1red"></div>';
  $colors.= '<div class="color color-purple" color="1purple"></div>';
  $colors.= '<div class="color color-orange" color="1orange"></div>';
  $colors.= '<div class="color color-blue" color="1blue"></div>';
  $colors.= '<div class="color color-reset" color="1">x</div>';
  $this->output.= '<div class="color-picker" style="display:none">' . $colors . '</div>';
  $val = 0;
  
  
  
  
  
  if (isset($_GET['filename'])) {
   $val = $this->data[$x.'_'.$y];
  }
  if (isset($_POST['cell_' . $x . '_'. $y])) {
   $val = $_POST['cell_' . $x . '_'. $y];
  }
  


  $this->output.= '<input class="cell-color" type="hidden" id="cellcolor_' . $x . '_'. $y .'" name="cell_' . $x . '_'. $y .'_color" value="' . $color . '">';  
  $this->output.= '<input class="cell" type="text" id="cell_' . $x . '_'. $y .'" name="cell_' . $x . '_' . $y .'" value="' . $val . '">';
  
  $this->output.= '</div>';
  
  
  $this->data[$x.'_'.$y] = $val;
  $this->data[$x.'_'.$y.'_color'] = $color;
  
  //print('<pre>' . print_r($this->data) . '</pre>');
 }
 
 
 
 public function draw() {
  
 
  if (isset($this->data['rowsnum'])) {
   $rowsnum = $this->data['rowsnum'];
  } else {
   $rowsnum = 3;
  }
  
   if (isset($_POST['rowsnum'])) {
    $rowsnum = $_POST['rowsnum'];
   }
   $this->data['rowsnum'] = $rowsnum;
   
  $this->output = '<div id="interface"><form id="diamond" name="diamond" method="POST">';
   for ($row = 0; $row < $rowsnum+1; $row++) {
    for ($c = 0; $c<$row; $c++) {
     $this->add($c, $row);
    }
   }
   $filename = '';
   
   if (isset($_GET['filename'])) {
    $filename = $_GET['filename'];
    $note = $this->data['note'];
    }
   
   if (isset($_POST['filename'])) {
    $filename = $_POST['filename'];
    $note = $_POST['note'];
   }
   
   if (isset($_POST['saved'])) {
    $saved = $_POST['saved'];
   }
   
  
 
   
   $this->output.= '<label>Sorok száma:</label><input type="text" name="rowsnum" value="' . $rowsnum . '"><br />';
   $this->output.= '<label>Fájlnév:</label><input id="filename" type="text" name="filename" value="' . $filename . '"><br />';
   $this->output.= '<label>Jegyzet:</label><input id="note" type="text" name="note" value="' . $note . '"><br />';
   $this->output.= '<input id="saved" type="text" name="saved" value="' . $saved . '"><br />';
   $this->output.= '<input id="submit" type="submit" value="mentés">';
   $this->output.= '</form></div>';
   print $this->output;
 }
 
 public function read_diamond() {
  $diamonds = '<div id="list_of_diamonds"><h2>Mentett sablonok</h2>';
  $files = array();
  if ($handle = opendir('./diamonds')) {
    while (false !== ($entry = readdir($handle))) {
        if ($entry != '.' && $entry != '..') {
            $files[] = $entry;
            
        }
    }
    closedir($handle);
  }
  ksort($files);
  foreach ($files as $key=>$entry) {
    $diamonds.= '<a href="index.php?filename=' . $entry . '">' . $entry . '</a></br>';
  }
  
  
  
  $diamonds.= '</div>';
  print $diamonds;
 }
 
 public function save_data() {
  if (isset($_POST['filename'])) {
    $filename = explode('.', $_POST['filename']);
    file_put_contents('diamonds/'.$filename[0] . '.txt', serialize($this->data));
  }
 }
 
 public function load_data() {
  $data = file_get_contents('diamonds/'.$_GET['filename']);
  $this->data = unserialize($data);
 }
}

$matrix = new diamond();

$matrix->load_data();

$matrix->draw();

$matrix->save_data();

$matrix->read_diamond();

?>


<script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function()

{
 var actcell = 0;
 var newcell = '';
  $('.diamond .cell').click(function() {
   actcell = $(this).val();
   newcell = '';
   console.log('ACT ki'+actcell);
   $(this).val('');
  })
  
  $('.diamond .cell').keydown(function(e) {
   newcell+= String.fromCharCode(e.which);
   $('#saved').val('NINCS MENTVE!');
  })
  
  $('.diamond .cell').blur(function() {
    $(this).parent().css('background-image','url(1.png)');
    if (newcell == '') {
      $(this).val(actcell);
    } else {
      $(this).val(newcell);
    }
    
    $(this).prev().prev().hide();
   
  })
  
  
  

  
  
  $('#diamond #submit').click(function() {
    if ($('#filename').val() == '') {
      alert('nincs fájlnév megadva!');
    } else {
      $('#saved').val('');
      $(this).submit();
    }
    
  })
  
  
  
  var cellbg = 1;
  
  $('.diamond').hover(function() {
        cellbg = $('.cell-color',this).val();
        $(this).css('background-image','url(1a.png)');
        $('.color-picker').hide();
        $('.color-picker', this).show();
      },
      function() {
        if ($('.cell',this).is(':focus')) {
      
        } else {
          
          $(this).css('background-image', 'url(' + cellbg + '.png)');
          $('.color-picker', this).hide();
        }
      }
    
  )
  
  $('.diamond .color-picker .color').click(function() {
    
    color = $(this).attr('color');
    $(this).parent().parent().css('background-image','url('+color+'.png)');
    $(this).parent().next().val(color);
    cellbg = color;
    $('#saved').val('NINCS MENTVE!');
    
  })
  
  $('.diamond .cell').focus(function() {
    $(this).parent().css('background-image','url(1a.png)');
  })

})
</script>




<!-- hitwebcounter Code START -->
<a href="http://www.hitwebcounter.com/" target="_blank" style="display:none;">
<img src="http://hitwebcounter.com/counter/counter.php?page=5245584&style=0001&nbdigits=5&type=page&initCount=0" title="best tracking stats" Alt="best tracking stats"   border="0" >
</a><br/>
<!-- hitwebcounter.com --><a href="http://www.hitwebcounter.com/countersiteservices.php" title="Websites Counter" 
target="_blank" style="font-family: Arial, Helvetica, sans-serif; 
font-size: 11px; color: #758087; text-decoration: none ;"><strong>Websites Counter</strong>
</a>  
</body>
</html>