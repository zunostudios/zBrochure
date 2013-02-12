<?php
/**
 * @version		v1.0.1
 * @package		Zuno.zBrochure
 * @subpackage	com_zbrochure
 * @copyright	Copyright (C) 2013 Zuno Enterprises, Inc. All rights reserved.
 */

defined('_JEXEC') or die('Restricted access'); ?>

<div class="two-col-form">
	
	<div class="inner">
	
		<div class="form-column">
						
			<div class="form-row">
			
				<ul class="text-colors">
					<li>
						<div id="headline-choose" class="color-select" onclick="getColors('headline');">
							<div class="fill" style="background-color:<?php echo $this->theme_data->headline; ?>"></div>
						</div>
						<h2 id="headline" class="color1 headline-color item-sample" style="color:<?php echo $this->theme_data->headline; ?>"><?php echo JText::_('HEADLINE_TEXT'); ?></h2>
						<input type="hidden" value="<?php echo $this->theme_data->headline; ?>" name="data[headline]" id="headline-input" />
						<div class="headline-choose text-picker"></div>
					</li>
					
					<li>
						<div id="subheading-choose" class="color-select" onclick="getColors('subheading');">
							<div class="fill" style="background-color:<?php echo $this->theme_data->subheading; ?>"></div>
						</div>
						<h3 id="subheading" class="color2 subheading-color item-sample" style="color:<?php echo $this->theme_data->subheading; ?>"><?php echo JText::_('SUBHEADING_LABEL'); ?></h3>
						<input type="hidden" value="<?php echo $this->theme_data->subheading; ?>" name="data[subheading]" id="subheading-input" />
						<div class="subheading-choose text-picker"></div>
					</li>
					
					<li>
						<div id="paragraph-choose" class="color-select" onclick="getColors('paragraph');">
							<div class="fill" style="background-color:<?php echo $this->theme_data->paragraph; ?>"></div>
						</div>
						<p id="paragraph" class="color3 paragraph-color item-sample" style="color:<?php echo $this->theme_data->paragraph; ?>"><?php echo JText::_('EXAMPLE_PARAGRAPH_TEXT'); ?>…</p>
						<input type="hidden" value="<?php echo $this->theme_data->paragraph; ?>" name="data[paragraph]" id="paragraph-input" />
						<div class="paragraph-choose text-picker"></div>
					</li>
				
					<li>
						<div class="reversed-selector">
							<div id="reversed-choose" class="color-select" onclick="getColors('reversed');">
								<div class="fill" style="background-color:<?php echo $this->theme_data->reversed; ?>"></div>
							</div>
							<p id="reversed-text" class="color4 reversed-color item-sample" style="color:<?php echo $this->theme_data->reversed; ?>"><?php echo JText::_('EXAMPLE_REVERSED_TEXT'); ?>…</p>
							<input type="hidden" value="<?php echo $this->theme_data->reversed; ?>" name="data[reversed]" id="reversed-input" />
							<div class="reversed-choose text-picker"></div>
						</div>
					</li>
				
					<li>
						<div class="callout-selector">
							<div id="callout-choose" class="color-select" onclick="getColors('callout');">
								<div class="fill" style="background-color:<?php echo $this->theme_data->callout; ?>"></div>
							</div>
							<p id="callout-text" class="color4 callout-color bg item-sample" style="background-color:<?php echo $this->theme_data->callout; ?>"><?php echo JText::_('EXAMPLE_CALLOUT_TEXT'); ?>…</p>
							<input type="hidden" value="<?php echo $this->theme_data->callout; ?>" name="data[callout]" id="callout-input" />
							<div class="callout-choose text-picker"></div>
						</div>
					</li>
				
				</ul>
														
			</div>
		
		</div>
		
		<div class="form-column fr">
											
			<h2 class="color1 headline-color" style="color:<?php echo $this->theme_data->headline; ?>"><?php echo JText::_('HEADLINE_TEXT'); ?></h2>
			<h3 class="color2 subheading-color" style="color:<?php echo $this->theme_data->subheading; ?>"><?php echo JText::_('SUBHEADING_LABEL'); ?></h3>
			<p class="color3 paragraph-color" style="color:<?php echo $this->theme_data->paragraph; ?>;margin:0 0 15px"><?php echo JText::_( 'THEME_PARAGRAPH_TEXT_SAMPLE' ); ?></p>
			<p class="color4 reversed-color reversed" style="color:<?php echo $this->theme_data->reversed; ?>;margin:0 0 15px"><?php echo JText::_( 'THEME_REVERSED_TEXT_SAMPLE' ); ?></p>
				
		</div>
		
		<div class="clear"><!--   --></div>
	
	</div>
	
</div>