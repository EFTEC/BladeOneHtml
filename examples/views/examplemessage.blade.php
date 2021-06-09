<h1>Testing messages</h1>
@@message(id='msg1')<br>
@message(id='msg1')<br>

@@message(id='msg1' level='error' default='')<br>
@message(id='msg1' level='error' default='')<br>

@@message(id='msg1' level='warning' default='no warning')<br>
@message(id='msg1' level='warning' default='no warning')<br>

@@message(id='msg1' level='info')<br>
@message(id='msg1' level='info')<br>

<h1>all messages from locker msg1</h1>

@messages(id='msg1')
    @items()
@endmessages()

<h1>errors from lockers msg1</h1>

@messages(id='msg1' level='error')
@items()
@endmessages()

<h1>warnings only from msg1</h1>

@messages(id='msg1' )
@items(level='warning')
@endmessages()

<h1>info only</h1>

@messages(id='msg1' )
@items(level='info')
@endmessages()

<h1>all of all</h1>

@messages()
@items()
@endmessages()
