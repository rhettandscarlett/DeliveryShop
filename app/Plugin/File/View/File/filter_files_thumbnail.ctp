
       <?php
      foreach ($files as $file_record){
        $file = $file_record['File'];
        echo $this->element('File.'.$itemInListTemplate, array(
          'file' => $file,
          'params'=>$params
        ));
      }
      ?>
  