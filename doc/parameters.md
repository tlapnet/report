# Report > Parameters

Skrze parametry lze upravovat výstup z datasource. Např. upravovat jeho SQL dotaz.

Všechny parametry jsou pod klíčem builder, protože pro svoje sestavení používají 
`ParametersBuilder`.

```yaml
params:
	builder:
        - input1
        - ......
        - inputN
```

## Inputy

`ParametersBuilder` podporuje tyto typy parametrů:

- text
- select

### Text

```yaml
params:
	builder:
        - addText({
            name: ID
            title: Identificator
            options: {
                placeholder: "Do not use me, I'm useless."
            }
            defaultValue: Foobar
            defaultValue: ::date('d.m.Y') # Call PHP native function
        })
```

### Select

```yaml
params:
	builder:
        - addSelect({
            name: country
            title: Countries
            prompt: Foobar
            items: [0 => "Czech Republic", 1 => "Slovakia"]
            defaultValue: 1
            defaultValue: ::date('Y') # Call PHP native function
            useKeys: true / false
            autopick: true / false # Pick first option
        })
```

`ParametersBuilder` vytvoří pole parametrů dle zadaných konfigurací a předá ho do subreportu.
Při vykreslování subreportu se pak ověřuje, zda-li existují nějaké parametry a je případně
vykreslen formulář.

Formulář je defaulně nevyplněný a input nejsou povinné. Při odeslání formuláře bez dat se nic neprovede,
až pokud se vyplnít alespoň 1 input / parameter. Výsledný vyhledávací dotaz se uloží do URL a je tedy možné,
odeslat link a dotyčnému by se měl zobrazit stejný výstup.
