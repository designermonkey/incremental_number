# Incremental Number Field

A field that automatically increments it's value by one for each new entry in a section.

## 1. Installation

1. Upload the `/incremental_number` folder in this archive to your Symphony `/extensions` folder.
2. Go to '**System → Extensions**' in your Symphony admin area.
3. Enable the extension by selecting '**Field: Incremental Number**', choose '**Enable**' from the '**With Selected…**' menu, then click '**Apply**'.

## 2. Usage

1. Add the field type '**Incremental Number**' to a section of your choice.
2. Define a valid '**Start Number**' for the field (has to be a natural number, e.g. `0` or `1`).
3. Now each time you create an entry in this section the '**Incremental Number**'-field will automatically get populated by fetching the '**Incremental Number**'-value of the previous entry and incrementing it by 1. If there is no previous entry in the section the field will instead get populated with the given '**Start Number**'.
4. You can't manually edit the values  of an '**Incremental Number Field**' – they're read-only.
