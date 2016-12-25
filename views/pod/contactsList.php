<?php

$cssAnsScriptFilesTheme = array(

'/plugins/perfect-scrollbar/src/perfect-scrollbar.css'
);

HtmlHelper::registerCssAndScriptsFiles($cssAnsScriptFilesTheme,Yii::app()->request->baseUrl);
?>
<div class="panel panel-white">
	<div class="panel-heading border-light bg-blue">
		<h4 class="panel-title"><i class="fa fa-calendar"></i> <?php echo Yii::t("common","Contact",null,Yii::app()->controller->module->id); ?></h4>
	</div>
	<div class="panel-tools">
		<?php if(( @$authorised || @$openEdition) && !isset($noAddLink) && isset(Yii::app()->session["userId"]) ) { ?>
			<a class="tooltips btn btn-xs btn-light-blue " data-placement="top" data-toggle="tooltip" data-original-title="<?php echo Yii::t("common","Add",null,Yii::app()->controller->module->id) ?>" href="javascript:;" onclick="openForm ( 'common','contact' )">
	    		<i class="fa fa-plus"></i> <?php echo Yii::t("common","Add") ?>
	    	</a>
		<?php } ?>
	</div>
	
	<div class="panel-body no-padding">
		<div class="panel-scroll height-230 ps-container">
				<?php
					
					$nbOldEvents = 0;
					$nbEventVisible = 0;
					if(isset($contacts) && count($contacts)>0 ) { ?>
					<table class="table table-striped table-hover" id="events">
						<tbody>
					<?php	
						foreach ($contacts as $keyContact => $contact) {						
					?>
						<tr class="" style="" id="<?php echo $keyContact;?>">
							<td class="center hidden-sm hidden-xs" style="padding-left: 18px; ">
								<?php  

								//$url = '#element.detail.type.'.Event::COLLECTION.'.id.'.$e["_id"]; 
								if(!empty($contact["id"])){
									$url = '#person.detail.id.'.$contact["id"];

								

									$id = $contact["id"];
									$o = Element::getInfos(Person::COLLECTION, $id);
									
									$icon='<img height="35" width="35" class="tooltips" data-placement="right" src="'.$this->module->assetsUrl.'/images/news/profile_default_l.png" data-placement="right" data-original-title="'.$o['name'].'">';
									$refIcon="fa-user";
									$redirect="person";
									
									?>
									<a href="#<?php echo $redirect; ?>.detail.id.<?php echo (string)$o['id'];?>" class="lbh" title="<?php echo $o['name'] ?>" class="btn no-padding ">

									<?php if(@$o["profilThumbImageUrl"]) { ?>
										<img width="50" height="50"  alt="image" class="tooltips" data-placement='right' src="<?php echo Yii::app()->createUrl('/'.$o['profilThumbImageUrl']) ?>" data-placement="top" data-original-title="<?php echo $o['name'] ?>">
									<?php }else{ 
										echo $icon;
									} ?>
									</a>
								<?php } 
								else 
								{ ?>
								<span class="lbh text-dark">
								<?php 
								$icon='<img height="35" width="35" class="tooltips" data-placement="right" src="'.$this->module->assetsUrl.'/images/news/profile_default_l.png" data-placement="right">';
								echo $icon;
								?>
									
								</span>
								<?php } ?>
							</td>
							<td>
								<?php if(!empty($contact["id"])){ ?>
								<a href="<?php echo $url?>" class="lbh text-dark">
								<?php }else{ ?>
								<span class="lbh text-dark">
								<?php } ?>
									<?php 
									if(!empty($contact["name"])) echo $contact["name"];
									if(!empty($contact["role"])){
									?>
									<br/><span class="text-extra-small"><?php echo @$contact["role"];?></span>
									<?php }
									if(!empty($contact["email"])){ ?>
									<br/><span class="text-extra-small"><?php echo @$contact["email"];?></span>
									<?php }
									if(!empty($contact["telephone"])){ ?>
									<br/><span class="text-extra-small">
										<?php 
										foreach ($contact["telephone"] as $keyTel => $tel) {
											if($keyTel > 0) echo " / ";
											echo $tel;
										} ?>
									</span>
								<?php }
								if(!empty($contact["id"])){ ?>
								</a>
								<?php }else{ ?>
								</span>
								<?php } ?>
								
							</td>
							<td>
								<a class="tooltips btn btn-xs btn-light-blue " data-placement="top" data-toggle="tooltip" data-original-title="<?php echo Yii::t("common","Update",null,Yii::app()->controller->module->id) ?>" href="javascript:;" onclick="">
						    		<i class="fa fa-pencil"></i>
						    	</a>
						    	<a class="tooltips btn btn-xs btn-light-blue " data-placement="top" data-toggle="tooltip" data-original-title="<?php echo Yii::t("common","Remove",null,Yii::app()->controller->module->id) ?>" href="javascript:;" onclick="">
						    		<i class="fa fa-trash"></i>
						    	</a>
								
							</td>
						</tr>
						<?php
						}
					}
					if(isset($contacts) && count($contacts)>0 ) { ?>
						</tbody>
					</table>
					<?php } ?>
		<?php if( $nbEventVisible == 0 && $nbOldEvents== 0) { ?>
			<div id="infoEventPod" class="padding-10" >
				<blockquote> 
					<?php 
						if($contextType==Event::CONTROLLER)
							$explain="Create sub-events to show the event's program.<br/>And Organize the event's sequence";
						else
							$explain="Publiez les événements que vous organisez";
						echo Yii::t("event",$explain); 
					?>
				</blockquote>
			</div>
		<?php } ?>

		</div>
	</div>
</div>

<script type="text/javascript">
	var nbOldEvents = <?php echo (String) @$nbOldEvents;?>;
	jQuery(document).ready(function() {	 
		if (nbOldEvents == 0) $("#showHideOldEvent").hide();

		var itemId = '<?php echo @$contextId;?>';
		$('.init-event').off().on("click", function(){
			$("#ajaxSV").html("<div class='cblock'><div class='centered'><i class='fa fa-cog fa-spin fa-2x icon-big text-center'></i> Loading</div></div>");
			$.subview({
				content : "#ajaxSV",
				onShow : function() {
					var url = "";
					url = baseUrl+"/"+moduleId+"/event/eventsv/id/"+itemId+"/type/<?php echo @$contextType ?>";
					getAjax("#ajaxSV", url, function(){bindEventSubViewEvents(); $(".new-event").trigger("click");}, "html");
				},
				onSave : function() {
					$('.form-event').submit();
				},
				onHide : function() {
					//$.hideSubview();
				}
			});
			
		})
	})

	function toogleOldEvent() {
		$(".oldEvent").toggle("slow");
		$("#infoLastButNotNew").toggle("slow");
	}

</script>