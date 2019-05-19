<?php
/**
 * Kunena Component
 *
 * @package         Kunena.Template.Aurelia
 * @subpackage      Layout.Widget
 *
 * @copyright       Copyright (C) 2008 - 2019 Kunena Team. All rights reserved.
 * @license         https://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link            https://www.kunena.org
 **/
defined('_JEXEC') or die();
use Joomla\CMS\Language\Text;

$this->addScript('summernote-bs4.js');
$this->addStyleSheet('summernote-bs4.css');
$this->addScript('jquery.caret.js');
$this->addScript('jquery.atwho.js');
$this->addStyleSheet('jquery.atwho.css');

$this->addScriptDeclaration('
(function($) {
  $.extend($.summernote.lang, {
    'en-GB': {
      font: {
        bold: "' . Text::_('COM_KUNENA_WYSIWYG_EDITOR_BOLD') . '",
        italic: "' . Text::_('COM_KUNENA_WYSIWYG_EDITOR_ITALIC') . '",
        underline: "' . Text::_('COM_KUNENA_WYSIWYG_EDITOR_UNDERLINE') . '",
        clear: "' . Text::_('COM_KUNENA_WYSIWYG_EDITOR_REMOVE_FONT_STYLE') . '",
        height: "' . Text::_('COM_KUNENA_WYSIWYG_EDITOR_LINE_HEIGHT') . '",
        name: "' . Text::_('COM_KUNENA_WYSIWYG_EDITOR_FONT_FAMILY') . '",
        strikethrough: "' . Text::_('COM_KUNENA_WYSIWYG_EDITOR_STRIKETHROUGH') . '",
        superscript: "' . Text::_('COM_KUNENA_WYSIWYG_EDITOR_SUPERSCRIPT') . '",
        subscript: "' . Text::_('COM_KUNENA_WYSIWYG_EDITOR_SUBSCRIPT') . '",
        size: "' . Text::_('COM_KUNENA_WYSIWYG_EDITOR_FONT_SIZE') . '"
      },
      image: {
        image: "'Image'",
        insert: "'Ins�rer une image'",
        resizeFull: "'Taille originale'",
        resizeHalf: "'Redimensionner � 50 %'",
        resizeQuarter: "'Redimensionner � 25 %'",
        floatLeft: "'Align� � gauche'",
        floatRight: "'Align� � droite'",
        floatNone: "'Pas dalignement'",
        shapeRounded: "'Forme: Rectangle arrondi'",
        shapeCircle: "'Forme: Cercle'",
        shapeThumbnail: "'Forme: Vignette'",
        shapeNone: "'Forme: Aucune'",
        dragImageHere: "'Faites glisser une image ou un texte dans ce cadre'",
        dropImage: "'Lachez limage ou le texte'",
        selectFromFiles: "'Choisir un fichier'",
        maximumFileSize: "'Taille de fichier maximale'",
        maximumFileSizeError: "'Taille maximale du fichier d�pass�e'",
        url: "'URL de limage'",
        remove: "'Supprimer limage'",
        original: "'Original'"
      },
      video: {
        video: "'Vid�o'",
        videoLink: "'Lien vid�o'"",
        insert: "'Ins�rer une vid�o'",
        url: "'URL de la vid�o'",
        providers: "'(YouTube, Vimeo, Vine, Instagram, DailyMotion ou Youku)'"
      },
      link: {
        link: "'Lien'",
        insert: "'Ins�rer un lien'",
        unlink: "'Supprimer un lien'",
        edit: "'Modifier'",
        textToDisplay: "'Texte � afficher'",
        url: "'URL du lien'",
        openInNewWindow: "'Ouvrir dans une nouvelle fen�tre'"
      },
      table: {
        table: "'Tableau'",
        addRowAbove: "'Ajouter une ligne au-dessus'",
        addRowBelow: "'Ajouter une ligne en dessous'",
        addColLeft: "'Ajouter une colonne � gauche'",
        addColRight: "'Ajouter une colonne � droite'",
        delRow: "'Supprimer la ligne'",
        delCol: "'Supprimer la colonne'",
        delTable: "'Supprimer le tableau'"
      },
      hr: {
        insert: "'Ins�rer une ligne horizontale'"
      },
      style: {
        style: "'Style'",
        p: "'Normal'",
        blockquote: "'Citation'",
        pre: "'Code source'",
        h1: "'Titre 1'",
        h2: "'Titre 2'",
        h3: "'Titre 3'",
        h4: "'Titre 4'",
        h5: "'Titre 5'",
        h6: "'Titre 6'"
      },
      lists: {
        unordered: "'Liste � puces'",
        ordered: "'Liste num�rot�e'"
      },
      options: {
        help: "'Aide'",
        fullscreen: "'Plein �cran'",
        codeview: "'Afficher le code HTML'"
      },
      paragraph: {
        paragraph: "'Paragraphe'",
        outdent: "'Diminuer le retrait'",
        indent: "'Augmenter le retrait'",
        left: "'Aligner � gauche'",
        center: "'Centrer'",
        right: "'Aligner � droite'",
        justify: "'Justifier'"
      },
      color: {
        recent: "'Derni�re couleur s�lectionn�e'",
        more: "'Plus de couleurs'",
        background: "'Couleur de fond'",
        foreground: "'Couleur de police'",
        transparent: "'Transparent'",
        setTransparent: "'D�finir la transparence'",
        reset: "'Restaurer'",
        resetToDefault: "'Restaurer la couleur par d�faut'"
      },
      shortcut: {
        shortcuts: "'Raccourcis'",
        close: "'Fermer'",
        textFormatting: "'Mise en forme du texte'",
        action: "'Action'",
        paragraphFormatting: "'Mise en forme des paragraphes'",
        documentStyle: "'Style du document'",
        extraKeys: "'Touches suppl�mentaires'"
      },
      help: {
        'insertParagraph': "'Ins�rer paragraphe'",
        'undo': "'D�faire la derni�re commande'",
        'redo': "'Refaire la derni�re commande'",
        'tab': "'Tabulation'",
        'untab': "'Tabulation arri�re'",
        'bold': "'Mettre en caract�re gras'",
        'italic': "'Mettre en italique'",
        'underline': "'Mettre en soulign�'",
        'strikethrough': "'Mettre en texte barr�'",
        'removeFormat': "'Nettoyer les styles'",
        'justifyLeft': "'Aligner � gauche'",
        'justifyCenter': "'Centrer'",
        'justifyRight': "'Aligner � droite'",
        'justifyFull': "'Justifier � gauche et � droite'",
        'insertUnorderedList': "'Basculer liste � puces'",
        'insertOrderedList': "'Basculer liste ordonn�e'",
        'outdent': "'Diminuer le retrait du paragraphe'",
        'indent': "'Augmenter le retrait du paragraphe'",
        'formatPara': "'Changer le paragraphe en cours en normal (P)'",
        'formatH1': "'Changer le paragraphe en cours en ent�te H1'",
        'formatH2': "'Changer le paragraphe en cours en ent�te H2'",
        'formatH3': "'Changer le paragraphe en cours en ent�te H3'",
        'formatH4': "'Changer le paragraphe en cours en ent�te H4'",
        'formatH5': "'Changer le paragraphe en cours en ent�te H5'",
        'formatH6': "'Changer le paragraphe en cours en ent�te H6'",
        'insertHorizontalRule': "'Ins�rer s�paration horizontale'",
        'linkDialog.show': "'Afficher fen�tre dhyperlien'"
      },
      history: {
        undo: "'Annuler la derni�re action'",
        redo: "'Restaurer la derni�re action annul�e'"
      },
      specialChar: {
        specialChar: "'CARACT�RES SP�CIAUX'",
        select: "'Choisir des caract�res sp�ciaux'"
      }
    }
  });
})(jQuery);
');

$this->ktemplate  = KunenaFactory::getTemplate();
$templatesettings = $this->ktemplate->params;
$settings         = $templatesettings->get('wysibb');
?>
<script>
$(document).ready(function() {
	  $('[id^=editor-]').summernote();
	});
</script>

<textarea class="col-md-12" name="message" id="editor-<?php echo $this->message->id; ?>" rows="12" tabindex="7"
          required="required"></textarea>
