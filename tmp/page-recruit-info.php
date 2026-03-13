<?php
$recruitSalon = (is_page('recruit/info-guiches')) ? 'guiches' : 'soho' ;


?>
<article class="">
<div class="c-headline__outer">
    <div class="l-row--container c-gutter__row jc__start">
      <div class="c-headline__frame" >
        <h1 class="c-headline__title c-headline__title--long"><span class="">RECRUIT</span></h1>
        <div class="c-headline__detail">
    <p class="">
    <span class="c-ttl__xsmall clr__3"><span class="clr__1">#</span> <?php echo $recruitSalon ?> 募集要項</span></p>
        </div>
      </div>
    </div>
  </div>

  <section class=" mt__7 mt__8--md">
    <div class="l-row--container c-gutter__row jc__start">
      <div class="c-col--12 c-col__sm--12 c-col__xl--10">
      <dl class="p-recruit-info__dl">

<?php
  get_template_part('tmp/content/recruit', 'info-'.$recruitSalon );
?>


          </dl>
      </div>
    </div>


  </section>

</article>

