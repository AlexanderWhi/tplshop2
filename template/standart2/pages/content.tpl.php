<?
if($this->isAdmin()){echo '<a href="/admin/content/?act=edit&id='.$this->mod_content_id.'" class="coin-text-edit" title="�������������"></a>';}
echo $this->getContent();
?>