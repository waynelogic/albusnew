export const initFormTriggers = ( $form ) => {
    if( !( $form instanceof HTMLElement ) )
        return false;

    const triggers = {};
    const formTags = ['input', 'select', 'textarea'];

    const fns = {
        'hide' : el => el.style.setProperty( 'display', 'none' ),
        'show' : el => el.style.removeProperty('display'),
        'enabled': el => {
            formTags.includes( el.tagName.toLowerCase() )
                ? el.disabled = false
                : el.querySelectorAll( formTags.join(',') ).forEach( field => field.disabled = false )
        },
        'disabled': el => {
            formTags.includes( el.tagName.toLowerCase() )
                ? el.disabled = true
                : el.querySelectorAll( formTags.join(',') ).forEach( field => field.disabled = true )
        },
        'empty': el => {
            const fields = formTags.includes( el.tagName.toLowerCase() )
                        ? [ el ]
                        : el.querySelectorAll( formTags.join(',') );

            fields.forEach( field => {
                if( ![ 'checkbox', 'radio', 'button', 'submit' ].includes( field.type ) ) {
                    field.value = '';
                }

                if( [ 'checkbox', 'radio' ].includes( field.type ) ) {
                    field.checked = false;
                }

                field.dispatchEvent( new Event( 'change', { bubbles : true } ) );
            });
        }
    }

    $form.querySelectorAll('[data-trigger-field]').forEach( $field => {
        const action = $field.dataset.triggerAction;
        const field = $field.dataset.triggerField;
        const condition = $field.dataset.triggerCondition;

        if( action === undefined || field === undefined || condition === undefined )
            return;

        if( triggers[ field ] === undefined )
            triggers[ field ] = [];

        triggers[ field ].push({
            el: $field,
            action,
            condition,
        });
    });

    const eventHandler = ({ target }) => processField( target );

    const toggleVisible = ( { action, condition, el }, value ) => {
        const reverseAction = action == 'show' ? 'hide' : 'show';
        fns[ value == condition ? action : reverseAction ]( el );
    }

    const toggleEnabled = ( { action, condition, el }, value ) => {
        const reverseAction = action == 'enabled' ? 'disabled' : 'enabled';
        fns[ value == condition ? action : reverseAction ]( el );
    }

    const checkAction = ( item, value ) => {
        if( [ 'show', 'hide' ].includes( item.action ) )
            toggleVisible( item, value );

        if( [ 'enabled', 'disabled' ].includes( item.action ) )
            toggleEnabled( item, value );

        if( item.action == 'empty' && item.condition == value ) {
            fns[ 'empty' ]( item.el );
        }
    }

    const processField = ( formField ) => {
        const value = [ 'checkbox', 'radio' ].includes( formField.type )
                        ? formField.checked ? 'checked' : 'unchecked'
                        : formField.value;
    
        const tr = triggers[ formField.name ] || [];
        tr.forEach( item => checkAction( item, value ) );
    }

    $form.querySelectorAll( formTags.join(',') ).forEach( formField => processField( formField ) );
    $form.addEventListener( 'input', eventHandler );
    $form.addEventListener( 'change', eventHandler );

    return () => {
        console.log('clear triggers');
        $form.removeEventListener( 'input', eventHandler );
        $form.removeEventListener( 'change', eventHandler );
    }
}