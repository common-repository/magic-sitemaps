<?php if(isset($settings)): ?>
<div class="wrap">
  <h2>Magic Sitemaps</h2>
  <div style="padding-bottom:10px;"></div>
  <?php echo $report;?>
  <form action="" method="post">
  <div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
      <div id="postbox-container-1" class="postbox-container">

      </div>
      <div id="postbox-container-2" class="postbox-container">
        <p>Your sitemaps here: <a href="<?php echo home_url('sitemap_index.xml');?>" target="_blank"><?php echo home_url('sitemap_index.xml');?></a></p>
        <p>If you find <strong>404</strong> Not Found after opened url above, please update permalink settings manually through <strong>Settings >>> Permalinks</strong> and press <strong>Save Changes</strong> button.</p>
        <div id="normal-sortables" class="meta-box-sortables ui-sortable">
          <div id="general-setting" class="postbox">
              <div class="inside">
                  <table class="form-table">
                    <tbody>
                      <tr>
                        <td colspan="3"><label class="checkbox inline"><input id="enable-post-sitemaps" type="checkbox" name="smwp[post_sitemap]" value="true" <?php echo $settings['post_sitemap']?'checked':'';?> onchange="smwpChangePost()"/> check this for enable Single Post Sitemaps</label></td>
                      </tr>
                      <tr id="post-changefreq"<?php echo $settings['post_sitemap']?'':' style="display:none"';?>>
                        <td></td>
                        <th scope="row"><label for="i-post-changefreq">Changefreq</label></th>
                        <td>
                          <select id="i-post-changefreq" name="smwp[post_changefreq]" style="width:100%" class="regular-text">
                            <option value="always" <?php echo $settings['post_changefreq'] == 'always'?'selected="true"':'';?>>always</option>
                            <option value="hourly" <?php echo $settings['post_changefreq'] == 'hourly'?'selected="true"':'';?>>hourly</option>
                            <option value="daily" <?php echo $settings['post_changefreq'] == 'daily'?'selected="true"':'';?>>daily</option>
                            <option value="weekly" <?php echo $settings['post_changefreq'] == 'weekly'?'selected="true"':'';?>>weekly</option>
                            <option value="monthly" <?php echo $settings['post_changefreq'] == 'monthly'?'selected="true"':'';?>>monthly</option>
                            <option value="yearly" <?php echo $settings['post_changefreq'] == 'yearly'?'selected="true"':'';?>>yearly</option>
                            <option value="never" <?php echo $settings['post_changefreq'] == 'never'?'selected="true"':'';?>>never</option>
                          </select>
                        </td>
                      </tr>
                      <tr id="post-priority"<?php echo $settings['post_sitemap']?'':' style="display:none"';?>>
                        <td></td>
                        <th scope="row"><label for="i-post-priority">Priority</label></th>
                        <td>
                          <select id="i-post-priority" name="smwp[post_priority]" style="width:100%" class="regular-text">
                            <option value="0.0" <?php echo $settings['post_priority'] == '0.0'?'selected="true"':'';?>>0.0</option>
                            <option value="0.1" <?php echo $settings['post_priority'] == '0.1'?'selected="true"':'';?>>0.1</option>
                            <option value="0.2" <?php echo $settings['post_priority'] == '0.2'?'selected="true"':'';?>>0.2</option>
                            <option value="0.3" <?php echo $settings['post_priority'] == '0.3'?'selected="true"':'';?>>0.3</option>
                            <option value="0.4" <?php echo $settings['post_priority'] == '0.4'?'selected="true"':'';?>>0.4</option>
                            <option value="0.5" <?php echo $settings['post_priority'] == '0.5'?'selected="true"':'';?>>0.5</option>
                            <option value="0.6" <?php echo $settings['post_priority'] == '0.6'?'selected="true"':'';?>>0.6</option>
                            <option value="0.7" <?php echo $settings['post_priority'] == '0.7'?'selected="true"':'';?>>0.7</option>
                            <option value="0.8" <?php echo $settings['post_priority'] == '0.8'?'selected="true"':'';?>>0.8</option>
                            <option value="0.9" <?php echo $settings['post_priority'] == '0.9'?'selected="true"':'';?>>0.9</option>
                            <option value="1.0" <?php echo $settings['post_priority'] == '1.0'?'selected="true"':'';?>>1.0</option>
                          </select>
                          
                        </td>
                      </tr>
                      <tr id="post-max-url"<?php echo $settings['post_sitemap']?'':' style="display:none"';?>>
                        <td></td>
                        <th scope="row"><label for="i-post-max-url">Max URL/Sitemaps</label></th>
                        <td>
                          <input type="number" id="i-post-max-url" name="smwp[post_max_url]" value="<?php echo $settings['post_max_url'];?>" style="width:100%" class="regular-text"/>
                          <p>how many url per sitemaps file.</p>
                        </td>
                      </tr>

                      <tr>
                        <td colspan="3"><label class="checkbox inline"><input id="enable-attachment-sitemaps" type="checkbox" name="smwp[attachment_sitemap]" value="true" <?php echo $settings['attachment_sitemap']?'checked':'';?> onchange="smwpChangeAttachment()"/> check this for enable Attachment Sitemaps</label></td>
                      </tr>
                      <tr id="attachment-changefreq"<?php echo $settings['attachment_sitemap']?'':' style="display:none"';?>>
                        <td></td>
                        <th scope="row"><label for="i-attachment-changefreq">Changefreq</label></th>
                        <td>
                          <select id="i-attachment-changefreq" name="smwp[attachment_changefreq]" style="width:100%" class="regular-text">
                            <option value="always" <?php echo $settings['attachment_changefreq'] == 'always'?'selected="true"':'';?>>always</option>
                            <option value="hourly" <?php echo $settings['attachment_changefreq'] == 'hourly'?'selected="true"':'';?>>hourly</option>
                            <option value="daily" <?php echo $settings['attachment_changefreq'] == 'daily'?'selected="true"':'';?>>daily</option>
                            <option value="weekly" <?php echo $settings['attachment_changefreq'] == 'weekly'?'selected="true"':'';?>>weekly</option>
                            <option value="monthly" <?php echo $settings['attachment_changefreq'] == 'monthly'?'selected="true"':'';?>>monthly</option>
                            <option value="yearly" <?php echo $settings['attachment_changefreq'] == 'yearly'?'selected="true"':'';?>>yearly</option>
                            <option value="never" <?php echo $settings['attachment_changefreq'] == 'never'?'selected="true"':'';?>>never</option>
                          </select>
                        </td>
                      </tr>
                      <tr id="attachment-priority"<?php echo $settings['attachment_sitemap']?'':' style="display:none"';?>>
                        <td></td>
                        <th scope="row"><label for="i-attachment-priority">Priority</label></th>
                        <td>
                          <select id="i-attachment-priority" name="smwp[attachment_priority]" style="width:100%" class="regular-text">
                            <option value="0.0" <?php echo $settings['attachment_priority'] == '0.0'?'selected="true"':'';?>>0.0</option>
                            <option value="0.1" <?php echo $settings['attachment_priority'] == '0.1'?'selected="true"':'';?>>0.1</option>
                            <option value="0.2" <?php echo $settings['attachment_priority'] == '0.2'?'selected="true"':'';?>>0.2</option>
                            <option value="0.3" <?php echo $settings['attachment_priority'] == '0.3'?'selected="true"':'';?>>0.3</option>
                            <option value="0.4" <?php echo $settings['attachment_priority'] == '0.4'?'selected="true"':'';?>>0.4</option>
                            <option value="0.5" <?php echo $settings['attachment_priority'] == '0.5'?'selected="true"':'';?>>0.5</option>
                            <option value="0.6" <?php echo $settings['attachment_priority'] == '0.6'?'selected="true"':'';?>>0.6</option>
                            <option value="0.7" <?php echo $settings['attachment_priority'] == '0.7'?'selected="true"':'';?>>0.7</option>
                            <option value="0.8" <?php echo $settings['attachment_priority'] == '0.8'?'selected="true"':'';?>>0.8</option>
                            <option value="0.9" <?php echo $settings['attachment_priority'] == '0.9'?'selected="true"':'';?>>0.9</option>
                            <option value="1.0" <?php echo $settings['attachment_priority'] == '1.0'?'selected="true"':'';?>>1.0</option>
                          </select>
                          
                        </td>
                      </tr>
                      <tr id="attachment-max-url"<?php echo $settings['attachment_sitemap']?'':' style="display:none"';?>>
                        <td></td>
                        <th scope="row"><label for="i-attachment-max-url">Max URL/Sitemaps</label></th>
                        <td>
                          <input type="number" id="i-attachment-max-url" name="smwp[attachment_max_url]" value="<?php echo $settings['attachment_max_url'];?>" style="width:100%" class="regular-text"/>
                          <p>how many url per sitemaps file.</p>
                        </td>
                      </tr>

                      <tr>
                        <td colspan="3"><label class="checkbox inline"><input id="enable-tag-sitemaps" type="checkbox" name="smwp[tag_sitemap]" value="true" <?php echo $settings['tag_sitemap']?'checked':'';?> onchange="smwpChangeTag()"/> check this for enable Post Tag Sitemaps</label></td>
                      </tr>
                      <tr id="tag-changefreq"<?php echo $settings['tag_sitemap']?'':' style="display:none"';?>>
                        <td></td>
                        <th scope="row"><label for="i-tag-changefreq">Changefreq</label></th>
                        <td>
                          <select id="i-tag-changefreq" name="smwp[tag_changefreq]" style="width:100%" class="regular-text">
                            <option value="always" <?php echo $settings['tag_changefreq'] == 'always'?'selected="true"':'';?>>always</option>
                            <option value="hourly" <?php echo $settings['tag_changefreq'] == 'hourly'?'selected="true"':'';?>>hourly</option>
                            <option value="daily" <?php echo $settings['tag_changefreq'] == 'daily'?'selected="true"':'';?>>daily</option>
                            <option value="weekly" <?php echo $settings['tag_changefreq'] == 'weekly'?'selected="true"':'';?>>weekly</option>
                            <option value="monthly" <?php echo $settings['tag_changefreq'] == 'monthly'?'selected="true"':'';?>>monthly</option>
                            <option value="yearly" <?php echo $settings['tag_changefreq'] == 'yearly'?'selected="true"':'';?>>yearly</option>
                            <option value="never" <?php echo $settings['tag_changefreq'] == 'never'?'selected="true"':'';?>>never</option>
                          </select>
                        </td>
                      </tr>
                      <tr id="tag-priority"<?php echo $settings['tag_sitemap']?'':' style="display:none"';?>>
                        <td></td>
                        <th scope="row"><label for="i-tag-priority">Priority</label></th>
                        <td>
                          <select id="i-tag-priority" name="smwp[tag_priority]" style="width:100%" class="regular-text">
                            <option value="0.0" <?php echo $settings['tag_priority'] == '0.0'?'selected="true"':'';?>>0.0</option>
                            <option value="0.1" <?php echo $settings['tag_priority'] == '0.1'?'selected="true"':'';?>>0.1</option>
                            <option value="0.2" <?php echo $settings['tag_priority'] == '0.2'?'selected="true"':'';?>>0.2</option>
                            <option value="0.3" <?php echo $settings['tag_priority'] == '0.3'?'selected="true"':'';?>>0.3</option>
                            <option value="0.4" <?php echo $settings['tag_priority'] == '0.4'?'selected="true"':'';?>>0.4</option>
                            <option value="0.5" <?php echo $settings['tag_priority'] == '0.5'?'selected="true"':'';?>>0.5</option>
                            <option value="0.6" <?php echo $settings['tag_priority'] == '0.6'?'selected="true"':'';?>>0.6</option>
                            <option value="0.7" <?php echo $settings['tag_priority'] == '0.7'?'selected="true"':'';?>>0.7</option>
                            <option value="0.8" <?php echo $settings['tag_priority'] == '0.8'?'selected="true"':'';?>>0.8</option>
                            <option value="0.9" <?php echo $settings['tag_priority'] == '0.9'?'selected="true"':'';?>>0.9</option>
                            <option value="1.0" <?php echo $settings['tag_priority'] == '1.0'?'selected="true"':'';?>>1.0</option>
                          </select>
                          
                        </td>
                      </tr>
                      <tr id="tag-max-url"<?php echo $settings['tag_sitemap']?'':' style="display:none"';?>>
                        <td></td>
                        <th scope="row"><label for="i-tag-max-url">Max URL/Sitemaps</label></th>
                        <td>
                          <input type="number" id="i-tag-max-url" name="smwp[tag_max_url]" value="<?php echo $settings['tag_max_url'];?>" style="width:100%" class="regular-text"/>
                          <p>how many url per sitemaps file.</p>
                        </td>
                      </tr>

                      <tr>
                        <td colspan="3"><label class="checkbox inline"><input id="enable-category-sitemaps" type="checkbox" name="smwp[category_sitemap]" value="true" <?php echo $settings['category_sitemap']?'checked':'';?> onchange="smwpChangeCategory()"/> check this for enable Post Category Sitemaps</label></td>
                      </tr>
                      <tr id="category-changefreq"<?php echo $settings['category_sitemap']?'':' style="display:none"';?>>
                        <td></td>
                        <th scope="row"><label for="i-category-changefreq">Changefreq</label></th>
                        <td>
                          <select id="i-category-changefreq" name="smwp[category_changefreq]" style="width:100%" class="regular-text">
                            <option value="always" <?php echo $settings['category_changefreq'] == 'always'?'selected="true"':'';?>>always</option>
                            <option value="hourly" <?php echo $settings['category_changefreq'] == 'hourly'?'selected="true"':'';?>>hourly</option>
                            <option value="daily" <?php echo $settings['category_changefreq'] == 'daily'?'selected="true"':'';?>>daily</option>
                            <option value="weekly" <?php echo $settings['category_changefreq'] == 'weekly'?'selected="true"':'';?>>weekly</option>
                            <option value="monthly" <?php echo $settings['category_changefreq'] == 'monthly'?'selected="true"':'';?>>monthly</option>
                            <option value="yearly" <?php echo $settings['category_changefreq'] == 'yearly'?'selected="true"':'';?>>yearly</option>
                            <option value="never" <?php echo $settings['category_changefreq'] == 'never'?'selected="true"':'';?>>never</option>
                          </select>
                        </td>
                      </tr>
                      <tr id="category-priority"<?php echo $settings['category_sitemap']?'':' style="display:none"';?>>
                        <td></td>
                        <th scope="row"><label for="i-category-priority">Priority</label></th>
                        <td>
                          <select id="i-category-priority" name="smwp[category_priority]" style="width:100%" class="regular-text">
                            <option value="0.0" <?php echo $settings['category_priority'] == '0.0'?'selected="true"':'';?>>0.0</option>
                            <option value="0.1" <?php echo $settings['category_priority'] == '0.1'?'selected="true"':'';?>>0.1</option>
                            <option value="0.2" <?php echo $settings['category_priority'] == '0.2'?'selected="true"':'';?>>0.2</option>
                            <option value="0.3" <?php echo $settings['category_priority'] == '0.3'?'selected="true"':'';?>>0.3</option>
                            <option value="0.4" <?php echo $settings['category_priority'] == '0.4'?'selected="true"':'';?>>0.4</option>
                            <option value="0.5" <?php echo $settings['category_priority'] == '0.5'?'selected="true"':'';?>>0.5</option>
                            <option value="0.6" <?php echo $settings['category_priority'] == '0.6'?'selected="true"':'';?>>0.6</option>
                            <option value="0.7" <?php echo $settings['category_priority'] == '0.7'?'selected="true"':'';?>>0.7</option>
                            <option value="0.8" <?php echo $settings['category_priority'] == '0.8'?'selected="true"':'';?>>0.8</option>
                            <option value="0.9" <?php echo $settings['category_priority'] == '0.9'?'selected="true"':'';?>>0.9</option>
                            <option value="1.0" <?php echo $settings['category_priority'] == '1.0'?'selected="true"':'';?>>1.0</option>
                          </select>
                          
                        </td>
                      </tr>

                      <tr id="save_changes">
                        <th scope="row"></th>
                        <td colspan="2">
                          <p class="submit">
                            <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
                          </p>
                        </td>
                      </tr>
                      <tr>
                    </tbody>
                  </table>
              </div>
          </div>
        </div>
      </div>
    </div>
  </div
  </form>
</div>
<script type="text/javascript">
  function smwpChangePost(){
    $ = jQuery;
    if($('#enable-post-sitemaps').is(":checked")){
      $('#post-changefreq').show();
      $('#post-priority').show();
      $('#post-max-url').show();
    }
    else{
      $('#post-changefreq').hide();
      $('#post-priority').hide();
      $('#post-max-url').hide();
    }
  }
  function smwpChangeAttachment(){
    $ = jQuery;
    if($('#enable-attachment-sitemaps').is(":checked")){
      $('#attachment-changefreq').show();
      $('#attachment-priority').show();
      $('#attachment-max-url').show();
    }
    else{
      $('#attachment-changefreq').hide();
      $('#attachment-priority').hide();
      $('#attachment-max-url').hide();
    }
  }
  function smwpChangeTag(){
    $ = jQuery;
    if($('#enable-tag-sitemaps').is(":checked")){
      $('#tag-changefreq').show();
      $('#tag-priority').show();
      $('#tag-max-url').show();
    }
    else{
      $('#tag-changefreq').hide();
      $('#tag-priority').hide();
      $('#tag-max-url').hide();
    }
  }
  function smwpChangeCategory(){
    $ = jQuery;
    if($('#enable-category-sitemaps').is(":checked")){
      $('#category-changefreq').show();
      $('#category-priority').show();
    }
    else{
      $('#category-changefreq').hide();
      $('#category-priority').hide();
    }
  }
</script>
<?php endif;?>