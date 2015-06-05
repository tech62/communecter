<div class="row">
<div class ="col-lg-4 col-md-12">
	<?php $this->renderPartial('../pod/sliderPhoto', array("itemId" => (string)$event["_id"], "type" => PHType::TYPE_EVENTS)) ?>
</div>

<div class="col-lg-4 col-md-12">
	<?php $this->renderPartial('dashboard/description',array( "event" => $event, "organizer" =>$organizer, "itemId" => (string)$event["_id"], "type" => PHType::TYPE_EVENTS)); ?>
</div>
<div class ="col-lg-4 col-md-12">
	 <?php $this->renderPartial('dashboard/attendees',array( "attending" => $attending)); ?>
</div>

</div>
<script type="text/javascript">
	contextMap= <?php echo json_encode($event)?>;
	var images = <?php echo json_encode($images) ?>;
	var contentKeyBase = "<?php echo $contentKeyBase ?>";
</script>