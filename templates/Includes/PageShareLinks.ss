<% cached 'page-share', $ID, $SiteConfig.LastEdited, $List(Page).max(LastEdited), $List(Page).count() %>
<% if $ShowSharingLinks %>
<div class="share">
	<div class="share-label"><%t PageShareLinks.SHARE "Share:" %></div>
	<% if $SocialShareConfig.SharingType == "AddThis" && $SocialShareConfig.AddThisCode %>
		<div class="addthis_inline_share_toolbox"> </div>
	<% else_if $SocialShareConfig.SharingType == "Buttons" %>
		<% if $SocialShareConfig.ShareOnFacebook %>
			<div class="share-button">
				<div id="fb-root"></div>
				<div class="fb-share-button" data-href="{$AbsoluteLink}" data-send="false" data-layout="button_count" data-width="90" data-show-faces="false"></div>
			</div>
		<% end_if %>
		<% if $SocialShareConfig.ShareOnTwitter %>
			<div class="share-button">
				<a href="https://twitter.com/share" class="twitter-share-button" data-url="{$AbsoluteLink}" data-counturl="{$AbsoluteLink}" data-count="horizontal" data-lang="en">Tweet</a>
			</div>
		<% end_if %>
		<% if $SocialShareConfig.ShareOnPinterest %>
			<div class="share-button">
				<a href="https://www.pinterest.com/pin/create/button/?url={$AbsoluteLink}" data-pin-do="buttonBookmark" ><img src="https://assets.pinterest.com/images/pidgets/pinit_fg_en_rect_gray_20.png" /></a>
			</div>
		<% end_if %>
		<% if $SocialShareConfig.ShareOnLinkedin %>
			<div class="share-button">
				<script type="IN/Share" data-counter="right" data-url="{$AbsoluteLink}"></script>
			</div>
		<% end_if %>
	<% else_if $SocialShareConfig.SharingType == "Links" %>
		<% if $SocialShareConfig.ShareOnFacebook %>
			<div class="share-link facebook">
				<a href="$FacebookShareLink" target="_blank">
                    <img src="$resourceURL('innoweb/silverstripe-social-share:client/dist/icons/facebook.svg')" height="16" width="16" alt="Facebook">
                </a>
			</div>
		<% end_if %>
		<% if $SocialShareConfig.ShareOnTwitter %>
			<div class="share-link x">
				<a href="$TwitterShareLink" target="_blank">
                    <img src="$resourceURL('innoweb/silverstripe-social-share:client/dist/icons/x.svg')" height="16" width="16" alt="X (Twitter)">
                </a>
			</div>
		<% end_if %>
		<% if $SocialShareConfig.ShareOnPinterest %>
			<div class="share-link pinterest">
				<a href="<% with $PinterestShareLink %>$Link&description=$Description<% if $Image %>&amp;media=$Image<% end_if %><% end_with %>" target="_blank">
                    <img src="$resourceURL('innoweb/silverstripe-social-share:client/dist/icons/pinterest.svg')" height="16" width="16" alt="Pinterest">
                </a>
			</div>
		<% end_if %>
		<% if $SocialShareConfig.ShareOnLinkedin %>
			<div class="share-link linkedin">
				<a href="$LinkedinShareLink" target="_blank">
                    <img src="$resourceURL('innoweb/silverstripe-social-share:client/dist/icons/linkedin.svg')" height="16" width="16" alt="LinkedIn">
                </a>
			</div>
		<% end_if %>
	<% end_if %>
</div>
<% end_if %>
<% end_cached %>
