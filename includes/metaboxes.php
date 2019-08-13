<?php
$metaboxes = array( //Metaboxes
  "hideheader" => array(
    "mbid" => "dtg_page_hideheader",
    "mbtitle" => "Hide Header",
    "mbscreen" => "page", //string or array of post types
    "mbcontext" => "side", //normal, side, advanced
    "mbpriority" => "default", //high, low, default
    "fields" => array( //Fields
      "fhideheader" => array(
        "fid" => "dtg_field_custom_hideheader",
        "ftype" => "select",
        "fsubtype" => "text",
        "flabel" => "Do you want to hide the header?",
        "fdefault" => "no",
        "attributes" => array(
          "class" => "dtg_custom_field hideheader",
          "options" => array(
            "no"=>"No",
            "yes"=>"Yes"
          )
        )
      )
    )
  )
);
?>
