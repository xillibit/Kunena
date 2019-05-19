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
        insert: "'Insérer une image'",
        resizeFull: "'Taille originale'",
        resizeHalf: "'Redimensionner à 50 %'",
        resizeQuarter: "'Redimensionner à 25 %'",
        floatLeft: "'Aligné à gauche'",
        floatRight: "'Aligné à droite'",
        floatNone: "'Pas dalignement'",
        shapeRounded: "'Forme: Rectangle arrondi'",
        shapeCircle: "'Forme: Cercle'",
        shapeThumbnail: "'Forme: Vignette'",
        shapeNone: "'Forme: Aucune'",
        dragImageHere: "'Faites glisser une image ou un texte dans ce cadre'",
        dropImage: "'Lachez limage ou le texte'",
        selectFromFiles: "'Choisir un fichier'",
        maximumFileSize: "'Taille de fichier maximale'",
        maximumFileSizeError: "'Taille maximale du fichier dépassée'",
        url: "'URL de limage'",
        remove: "'Supprimer limage'",
        original: "'Original'"
      },
      video: {
        video: "'Vidéo'",
        videoLink: "'Lien vidéo'"",
        insert: "'Insérer une vidéo'",
        url: "'URL de la vidéo'",
        providers: "'(YouTube, Vimeo, Vine, Instagram, DailyMotion ou Youku)'"
      },
      link: {
        link: "'Lien'",
        insert: "'Insérer un lien'",
        unlink: "'Supprimer un lien'",
        edit: "'Modifier'",
        textToDisplay: "'Texte à afficher'",
        url: "'URL du lien'",
        openInNewWindow: "'Ouvrir dans une nouvelle fenêtre'"
      },
      table: {
        table: "'Tableau'",
        addRowAbove: "'Ajouter une ligne au-dessus'",
        addRowBelow: "'Ajouter une ligne en dessous'",
        addColLeft: "'Ajouter une colonne à gauche'",
        addColRight: "'Ajouter une colonne à droite'",
        delRow: "'Supprimer la ligne'",
        delCol: "'Supprimer la colonne'",
        delTable: "'Supprimer le tableau'"
      },
      hr: {
        insert: "'Insérer une ligne horizontale'"
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
        unordered: "'Liste à puces'",
        ordered: "'Liste numérotée'"
      },
      options: {
        help: "'Aide'",
        fullscreen: "'Plein écran'",
        codeview: "'Afficher le code HTML'"
      },
      paragraph: {
        paragraph: "'Paragraphe'",
        outdent: "'Diminuer le retrait'",
        indent: "'Augmenter le retrait'",
        left: "'Aligner à gauche'",
        center: "'Centrer'",
        right: "'Aligner à droite'",
        justify: "'Justifier'"
      },
      color: {
        recent: "'Dernière couleur sélectionnée'",
        more: "'Plus de couleurs'",
        background: "'Couleur de fond'",
        foreground: "'Couleur de police'",
        transparent: "'Transparent'",
        setTransparent: "'Définir la transparence'",
        reset: "'Restaurer'",
        resetToDefault: "'Restaurer la couleur par défaut'"
      },
      shortcut: {
        shortcuts: "'Raccourcis'",
        close: "'Fermer'",
        textFormatting: "'Mise en forme du texte'",
        action: "'Action'",
        paragraphFormatting: "'Mise en forme des paragraphes'",
        documentStyle: "'Style du document'",
        extraKeys: "'Touches supplémentaires'"
      },
      help: {
        'insertParagraph': "'Insérer paragraphe'",
        'undo': "'Défaire la dernière commande'",
        'redo': "'Refaire la dernière commande'",
        'tab': "'Tabulation'",
        'untab': "'Tabulation arrière'",
        'bold': "'Mettre en caractère gras'",
        'italic': "'Mettre en italique'",
        'underline': "'Mettre en souligné'",
        'strikethrough': "'Mettre en texte barré'",
        'removeFormat': "'Nettoyer les styles'",
        'justifyLeft': "'Aligner à gauche'",
        'justifyCenter': "'Centrer'",
        'justifyRight': "'Aligner à droite'",
        'justifyFull': "'Justifier à gauche et à droite'",
        'insertUnorderedList': "'Basculer liste à puces'",
        'insertOrderedList': "'Basculer liste ordonnée'",
        'outdent': "'Diminuer le retrait du paragraphe'",
        'indent': "'Augmenter le retrait du paragraphe'",
        'formatPara': "'Changer le paragraphe en cours en normal (P)'",
        'formatH1': "'Changer le paragraphe en cours en entête H1'",
        'formatH2': "'Changer le paragraphe en cours en entête H2'",
        'formatH3': "'Changer le paragraphe en cours en entête H3'",
        'formatH4': "'Changer le paragraphe en cours en entête H4'",
        'formatH5': "'Changer le paragraphe en cours en entête H5'",
        'formatH6': "'Changer le paragraphe en cours en entête H6'",
        'insertHorizontalRule': "'Insérer séparation horizontale'",
        'linkDialog.show': "'Afficher fenêtre dhyperlien'"
      },
      history: {
        undo: "'Annuler la dernière action'",
        redo: "'Restaurer la dernière action annulée'"
      },
      specialChar: {
        specialChar: "'CARACTÈRES SPÉCIAUX'",
        select: "'Choisir des caractères spéciaux'"
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
