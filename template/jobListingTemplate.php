<div id="jobListing" style="background-color:<?php echo $job_background_color; ?>">
	<div id="jobTitle"><?php echo $job_title; ?></div>
	<div id="jobDescription"><?php echo wpautop($job_description); ?></div>
	<div id="jobApplyButton">
		<a class="btn" href="<?php echo $apply_button_url; ?>" style="background:<?php echo $apply_button_color; ?>"><?php echo $apply_button_text; ?></a>
	</div>
</div>
