{table_open}<table class="calendar" border="0" cellpadding="0" cellspacing="0">{/table_open}

{heading_row_start}<thead>{/heading_row_start}

{heading_previous_cell}<th><a href="{previous_url}" id="previous"></a></th>{/heading_previous_cell}
{heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
{heading_next_cell}<th><a href="{next_url}" id="next"></a></th>{/heading_next_cell}

{heading_row_end}</thead>{/heading_row_end}

{week_row_start}<tr class="weekdays">{/week_row_start}
{week_day_cell}<td>{week_day}</td>{/week_day_cell}
{week_row_end}</tr>{/week_row_end}

{cal_row_start}<tr class="days">{/cal_row_start}
{cal_cell_start}<td>{/cal_cell_start}

{cal_cell_content}
   <div class="day-content">{day}</div>
   {content}
{/cal_cell_content}
{cal_cell_content_today}
   <div class="day-highlight">{day}</div>
   {content}
{/cal_cell_content_today}

{cal_cell_no_content}
   <div class="day">{day}</div>
{/cal_cell_no_content}
{cal_cell_no_content_today}
   <div class="day-highlight">{day}</div>
{/cal_cell_no_content_today}

{cal_cell_blank}&nbsp;{/cal_cell_blank}

{cal_cell_end}</td>{/cal_cell_end}
{cal_row_end}</tr>{/cal_row_end}

{table_close}</table>{/table_close}