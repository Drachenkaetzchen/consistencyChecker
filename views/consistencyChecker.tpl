[{include file="headitem.tpl" title="GENERAL_ADMIN_TITLE"|oxmultilangassign}]

<script type="text/javascript">
function _groupExp(el) {
    var _cur = el.parentNode;

    if (_cur.className == "exp") _cur.className = "";
      else _cur.className = "exp";
}
</script>
<form name="myedit" id="myedit" action="[{ $oViewConf->getSelfLink() }]" method="post">
    [{ $oViewConf->getHiddenSid() }]
    <input type="hidden" name="cl" value="consistencyChecker">
    <input type="hidden" name="fnc" value="execute">
    <input type="hidden" name="oxid" value="[{$oxid}]">

    <p>Dieses Tool erzeugt eine Konsistenzliste der OXID-Nummerierung der folgenden Daten:</p>
    <ul>
        [{foreach from=$config item=entry}]
            <li>[{$entry.canonicalTable}]: [{$entry.canonicalField}]</li>
        [{/foreach}]
    </ul>

    <p>
        Die Erzeugung der Auswertung kann je nach Servergeschwindigkeit mehrere Minuten dauern. Sollte ein Fehler
        auftreten, so reicht möglicherweise die Geschwindigkeit des Servers nicht aus.</p>


    [{foreach from=$resultData item=entry}]
        [{if $entry.results}]
            <h1>[{$entry.canonicalTable}]: [{$entry.canonicalField}]</h1>

            <div class="groupExp">
                <div class="">
                    <a href="#" onclick="_groupExp(this);return false;" class="rc">
                        <b>Duplikate: [{$entry.results.duplicateCount}]</b>
                    </a>
                    <dl>
                        [{foreach from=$entry.results.duplicates item=duplicate}]
                            [{$duplicate.field}]: [{$duplicate.cnt}] Duplikate<br/>
                        [{/foreach}]
                        </dl>
                </div>
            </div>

            <div class="groupExp">
                <div class="">
                    <a href="#" onclick="_groupExp(this);return false;" class="rc">
                        <b>Lücken: [{$entry.results.numberOfGaps}] ([{$entry.results.missingNumbers}] fehlend)</b>
                    </a>
                    <dl>
                        [{foreach from=$entry.results.gaps item=gap}]
                            Lücke zwischen [{$gap.previousNumber}] und [{$gap.currentNumber}] ([{$gap.missingNumbers}] fehlend)<br/>
                        [{/foreach}]
                        </dl>
                </div>
            </div>
        [{/if}]
    [{/foreach}]

    <input type="submit" class="edittext" name="preview"
           value="Auswertung erzeugen" [{$readonly}]"/>
</form>

[{include file="bottomnaviitem.tpl"}]
[{include file="bottomitem.tpl"}]
