<div class="events">
	<div  class="event-month"><?php echo date('M'); ?></div>
	<div class="event-day">
		<span class="dailyevent-day"><?php echo date('j'); ?></span>
		<span class="dailyevent-datesuffix"><?php echo date('S'); ?></span>
	</div>
</div>
<h2 style="text-align:center;">Today's Events</h2>
<div class="event-detail">
	{deEvents}
</div>