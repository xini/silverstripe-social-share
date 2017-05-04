<% cached 'base-share', ID, SiteConfig.LastEdited, List(Page).max(LastEdited), List(Page).count() %>
<div class="share">
	<div class="share-label"><%t BaseShareLinks.SHARE "Share:" %></div>
	<% if SocialShareConfig.SharingType == "AddThis" && SocialShareConfig.AddThisCode %>
		<div class="addthis_inline_share_toolbox"> </div>
	<% else_if SocialShareConfig.SharingType == "Buttons" %>
		<% if SocialShareConfig.ShareOnFacebook %>
			<div class="share-button">
				<div id="fb-root"></div>
				<div class="fb-like" data-href="{$BaseHref}" data-send="false" data-layout="button_count" data-width="90" data-show-faces="false"></div>
			</div>
		<% end_if %>
		<% if SocialShareConfig.ShareOnTwitter %>
			<div class="share-button">
				<a href="https://twitter.com/share" class="twitter-share-button" data-text="$SocialShareConfig.Title" data-url="{$BaseHref}" data-counturl="{$BaseHref}" data-count="horizontal" data-lang="en">Tweet</a>
			</div>
		<% end_if %>
		<% if SocialShareConfig.ShareOnGoogle %>
			<div class="share-button">
				<div class="g-plus" data-action="share" data-annotation="bubble" data-href="{$BaseHref}"></div>
			</div>
		<% end_if %>
		<% if SocialShareConfig.PlusOnGoogle %>
			<div class="share-button">
				<div class="g-plusone" data-size="medium" data-href="{$BaseHref}"></div>
			</div>
		<% end_if %>
		<% if SocialShareConfig.ShareOnPinterest %>
			<div class="share-button">
				<a href="//www.pinterest.com/pin/create/button/?url={$BaseHref}" data-pin-do="buttonBookmark" ><img src="//assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a>
			</div>
		<% end_if %>
		<% if SocialShareConfig.ShareOnLinkedin %>
			<div class="share-button">
				<script type="IN/Share" data-counter="right" data-url="{$BaseHref}"></script>
			</div>
		<% end_if %>
	<% else_if SocialShareConfig.SharingType == "Links" %>
		<% if SocialShareConfig.ShareOnFacebook %>
			<div class="share-link facebook">
				<a href="$FacebookShareLink(true)" target="_blank">Facebook</a>
			</div>
		<% end_if %>
		<% if SocialShareConfig.ShareOnTwitter %>
			<div class="share-link twitter">
				<a href="$TwitterShareLink(true)" target="_blank">Twitter</a>
			</div>
		<% end_if %>
		<% if SocialShareConfig.ShareOnGoogle %>
			<div class="share-link google">
				<a href="$GoogleShareLink(true)" target="_blank">Google+</a>
			</div>
		<% end_if %>
		<% if SocialShareConfig.ShareOnPinterest %>
			<div class="share-link pinterest">
				<a href="<% with $PinterestShareLink %>$Link&description=$Description<% if Image %>&amp;media=$Image<% end_if %><% end_with %>" target="_blank">Pinterest</a>
			</div>
		<% end_if %>
		<% if SocialShareConfig.ShareOnLinkedin %>
			<div class="share-link linkedin">
				<a href="$LinkedinShareLink(true)" target="_blank">LinkedIn</a>
			</div>
		<% end_if %>
	<% end_if %>
</div>
<% end_cached %>