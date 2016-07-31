<?php

interface IFeatureItem {
    
    public function add($featureId, $data);    
    public function update($data);    
    public function delete($id);   
    public function getList($id);      
    public function getDetail($id);      
    
    
}

  
?>
