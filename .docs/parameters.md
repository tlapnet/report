# Parameters

Through parameters could be modified output from datasource.

All parameters are under key `builder`, because they are using `ParametersBuilder`.

```yaml
params:
    builder:
        - input1
        - ......
        - inputN
```

## Builder

`ParametersBuilder` create array of parameters from entered configuration and add it to subreport.
During subreport render is checked, if exist any parameters. If they exist then form is rendered.

Form is not filled and inputs are required by default. If form is send empty then nothing happens.
At least one input (parameter) must be filled.

## Inputs

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
