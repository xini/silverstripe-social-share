<% cached 'page-share', ID, SiteConfig.LastEdited, List(Page).max(LastEdited), List(Page).count() %>
<div class="share">
	<div class="share-label"><%t PageShareLinks.SHARE "Share:" %></div>
	<% if SocialShareConfig.SharingType == "AddThis" && SocialShareConfig.AddThisCode %>
		<div class="addthis_inline_share_toolbox"> </div>
	<% else_if SocialShareConfig.SharingType == "Buttons" %>
		<% if SocialShareConfig.ShareOnFacebook %>
			<div class="share-button">
				<div id="fb-root"></div>
				<div class="fb-like" data-href="{$AbsoluteLink}" data-send="false" data-layout="button_count" data-width="90" data-show-faces="false"></div>
			</div>
		<% end_if %>
		<% if SocialShareConfig.ShareOnTwitter %>
			<div class="share-button">
				<a href="https://twitter.com/share" class="twitter-share-button" data-url="{$AbsoluteLink}" data-counturl="{$AbsoluteLink}" data-count="horizontal" data-lang="en">Tweet</a>
			</div>
		<% end_if %>
		<% if SocialShareConfig.ShareOnGoogle %>
			<div class="share-button">
				<div class="g-plus" data-action="share" data-annotation="bubble" data-href="{$AbsoluteLink}"></div>
			</div>
		<% end_if %>
		<% if SocialShareConfig.PlusOnGoogle %>
			<div class="share-button">
				<div class="g-plusone" data-size="medium" data-href="{$AbsoluteLink}"></div>
			</div>
		<% end_if %>
		<% if SocialShareConfig.ShareOnPinterest %>
			<div class="share-button">
				<a href="//www.pinterest.com/pin/create/button/?url={$AbsoluteLink}" data-pin-do="buttonBookmark" ><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a>
			</div>
		<% end_if %>
		<% if SocialShareConfig.ShareOnLinkedin %>
			<div class="share-button">
				<script type="IN/Share" data-counter="right" data-url="{$AbsoluteLink}"></script>
			</div>
		<% end_if %>
	<% else_if SocialShareConfig.SharingType == "Links" %>
		<% if SocialShareConfig.ShareOnFacebook %>
			<div class="share-link facebook">
				<a href="$FacebookShareLink" target="_blank">Facebook</a>
			</div>
		<% end_if %>
		<% if SocialShareConfig.ShareOnTwitter %>
			<div class="share-link twitter">
				<a href="$TwitterShareLink" target="_blank">Twitter</a>
			</div>
		<% end_if %>
		<% if SocialShareConfig.ShareOnGoogle %>
			<div class="share-link google">
				<a href="$GoogleShareLink" target="_blank">Google+</a>
			</div>
		<% end_if %>
		<% if SocialShareConfig.ShareOnPinterest %>
			<div class="share-link pinterest">
				<a href="<% with $PinterestShareLink %>$Link&description=$Description<% if Image %>&amp;media=$Image<% end_if %><% end_with %>" target="_blank">Pinterest</a>
			</div>
		<% end_if %>
		<% if SocialShareConfig.ShareOnLinkedin %>
			<div class="share-link linkedin">
				<a href="$LinkedinShareLink" target="_blank">LinkedIn</a>
			</div>
		<% end_if %>
	<% end_if %>
</div>
<% end_cached %>