# Build a Voice Agent

GET /v1/agent/converse

Build a conversational voice agent using Deepgram's Voice Agent WebSocket

Reference: https://developers.deepgram.com/reference/voice-agent/voice-agent

## AsyncAPI Specification

```yaml
asyncapi: 2.6.0
info:
  title: agent.v1
  version: subpackage_agent/v1.agent.v1
  description: Build a conversational voice agent using Deepgram's Voice Agent WebSocket
channels:
  /v1/agent/converse:
    description: Build a conversational voice agent using Deepgram's Voice Agent WebSocket
    bindings:
      ws:
        headers:
          type: object
          properties:
            Authorization:
              type: string
    publish:
      operationId: agent-v-1-publish
      summary: Server messages
      message:
        oneOf:
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-server-0-AgentV1ReceiveFunctionCallResponse
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-server-1-AgentV1PromptUpdated
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-server-2-AgentV1SpeakUpdated
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-server-3-AgentV1ThinkUpdated
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-server-4-AgentV1InjectionRefused
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-server-5-AgentV1Welcome
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-server-6-AgentV1SettingsApplied
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-server-7-AgentV1ConversationText
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-server-8-AgentV1UserStartedSpeaking
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-server-9-AgentV1AgentThinking
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-server-10-AgentV1FunctionCallRequest
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-server-11-AgentV1AgentStartedSpeaking
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-server-12-AgentV1AgentAudioDone
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-server-13-AgentV1Error
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-server-14-AgentV1Warning
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-server-15-AgentV1History
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-server-16-AgentV1Audio
    subscribe:
      operationId: agent-v-1-subscribe
      summary: Client messages
      message:
        oneOf:
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-client-0-AgentV1Settings
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-client-1-AgentV1UpdateSpeak
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-client-2-AgentV1InjectUserMessage
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-client-3-AgentV1InjectAgentMessage
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-client-4-AgentV1SendFunctionCallResponse
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-client-5-AgentV1KeepAlive
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-client-6-AgentV1UpdatePrompt
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-client-7-AgentV1UpdateThink
          - $ref: >-
              #/components/messages/subpackage_agent/v1.agent.v1-client-8-AgentV1Media
servers:
  Production:
    url: wss://agent.deepgram.com/
    protocol: wss
    x-default: true
components:
  messages:
    subpackage_agent/v1.agent.v1-server-0-AgentV1ReceiveFunctionCallResponse:
      name: AgentV1ReceiveFunctionCallResponse
      title: AgentV1ReceiveFunctionCallResponse
      description: |
        Receive a function call response from the server after the server
        has executed a server-side function call internally. This occurs
        when functions are marked with `client_side: false`.
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1ReceiveFunctionCallResponse'
    subpackage_agent/v1.agent.v1-server-1-AgentV1PromptUpdated:
      name: AgentV1PromptUpdated
      title: AgentV1PromptUpdated
      description: Receive prompt update from Deepgram's Voice Agent API
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1PromptUpdated'
    subpackage_agent/v1.agent.v1-server-2-AgentV1SpeakUpdated:
      name: AgentV1SpeakUpdated
      title: AgentV1SpeakUpdated
      description: Receive speak update from Deepgram's Voice Agent API
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1SpeakUpdated'
    subpackage_agent/v1.agent.v1-server-3-AgentV1ThinkUpdated:
      name: AgentV1ThinkUpdated
      title: AgentV1ThinkUpdated
      description: Receive think update from Deepgram's Voice Agent API
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1ThinkUpdated'
    subpackage_agent/v1.agent.v1-server-4-AgentV1InjectionRefused:
      name: AgentV1InjectionRefused
      title: AgentV1InjectionRefused
      description: Receive injection refused message from Deepgram's Voice Agent API
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1InjectionRefused'
    subpackage_agent/v1.agent.v1-server-5-AgentV1Welcome:
      name: AgentV1Welcome
      title: AgentV1Welcome
      description: Receive welcome message from Deepgram's Voice Agent API
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1Welcome'
    subpackage_agent/v1.agent.v1-server-6-AgentV1SettingsApplied:
      name: AgentV1SettingsApplied
      title: AgentV1SettingsApplied
      description: Receive settings applied message from Deepgram's Voice Agent API
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1SettingsApplied'
    subpackage_agent/v1.agent.v1-server-7-AgentV1ConversationText:
      name: AgentV1ConversationText
      title: AgentV1ConversationText
      description: Receive conversation text from Deepgram's Voice Agent API
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1ConversationText'
    subpackage_agent/v1.agent.v1-server-8-AgentV1UserStartedSpeaking:
      name: AgentV1UserStartedSpeaking
      title: AgentV1UserStartedSpeaking
      description: Receive user started speaking message from Deepgram's Voice Agent API
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1UserStartedSpeaking'
    subpackage_agent/v1.agent.v1-server-9-AgentV1AgentThinking:
      name: AgentV1AgentThinking
      title: AgentV1AgentThinking
      description: Receive agent thinking message from Deepgram's Voice Agent API
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1AgentThinking'
    subpackage_agent/v1.agent.v1-server-10-AgentV1FunctionCallRequest:
      name: AgentV1FunctionCallRequest
      title: AgentV1FunctionCallRequest
      description: Receive function call request from Deepgram's Voice Agent API
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1FunctionCallRequest'
    subpackage_agent/v1.agent.v1-server-11-AgentV1AgentStartedSpeaking:
      name: AgentV1AgentStartedSpeaking
      title: AgentV1AgentStartedSpeaking
      description: Receive agent started speaking message from Deepgram's Voice Agent API
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1AgentStartedSpeaking'
    subpackage_agent/v1.agent.v1-server-12-AgentV1AgentAudioDone:
      name: AgentV1AgentAudioDone
      title: AgentV1AgentAudioDone
      description: Receive agent audio done message from Deepgram's Voice Agent API
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1AgentAudioDone'
    subpackage_agent/v1.agent.v1-server-13-AgentV1Error:
      name: AgentV1Error
      title: AgentV1Error
      description: Receive error response from Deepgram's Voice Agent API
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1Error'
    subpackage_agent/v1.agent.v1-server-14-AgentV1Warning:
      name: AgentV1Warning
      title: AgentV1Warning
      description: Receive warning messages from Deepgram's Voice Agent API
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1Warning'
    subpackage_agent/v1.agent.v1-server-15-AgentV1History:
      name: AgentV1History
      title: AgentV1History
      description: >-
        Receive a conversation history message from Deepgram's Voice Agent API.
        Each message is either a conversation text (with role and content) or a
        function call record (with function_calls array).
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1History'
    subpackage_agent/v1.agent.v1-server-16-AgentV1Audio:
      name: AgentV1Audio
      title: AgentV1Audio
      description: Receive raw binary audio data generated by Deepgram's Voice Agent API
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1Audio'
    subpackage_agent/v1.agent.v1-client-0-AgentV1Settings:
      name: AgentV1Settings
      title: AgentV1Settings
      description: Send settings configuration to Deepgram's Voice Agent API
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1Settings'
    subpackage_agent/v1.agent.v1-client-1-AgentV1UpdateSpeak:
      name: AgentV1UpdateSpeak
      title: AgentV1UpdateSpeak
      description: Send update speak to Deepgram's Voice Agent API
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1UpdateSpeak'
    subpackage_agent/v1.agent.v1-client-2-AgentV1InjectUserMessage:
      name: AgentV1InjectUserMessage
      title: AgentV1InjectUserMessage
      description: Send inject user message to Deepgram's Voice Agent API
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1InjectUserMessage'
    subpackage_agent/v1.agent.v1-client-3-AgentV1InjectAgentMessage:
      name: AgentV1InjectAgentMessage
      title: AgentV1InjectAgentMessage
      description: Send inject agent message to Deepgram's Voice Agent API
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1InjectAgentMessage'
    subpackage_agent/v1.agent.v1-client-4-AgentV1SendFunctionCallResponse:
      name: AgentV1SendFunctionCallResponse
      title: AgentV1SendFunctionCallResponse
      description: |
        Send a function call response from the client to the server after
        executing a client-side function call. This is used when the server
        requests execution of a function marked with `client_side: true`.
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1SendFunctionCallResponse'
    subpackage_agent/v1.agent.v1-client-5-AgentV1KeepAlive:
      name: AgentV1KeepAlive
      title: AgentV1KeepAlive
      description: Send keep alive to Deepgram's Voice Agent API
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1KeepAlive'
    subpackage_agent/v1.agent.v1-client-6-AgentV1UpdatePrompt:
      name: AgentV1UpdatePrompt
      title: AgentV1UpdatePrompt
      description: Send a prompt update to Deepgram's Voice Agent API
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1UpdatePrompt'
    subpackage_agent/v1.agent.v1-client-7-AgentV1UpdateThink:
      name: AgentV1UpdateThink
      title: AgentV1UpdateThink
      description: Send update think to Deepgram's Voice Agent API
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1UpdateThink'
    subpackage_agent/v1.agent.v1-client-8-AgentV1Media:
      name: AgentV1Media
      title: AgentV1Media
      description: Send raw binary audio data to Deepgram's Voice Agent API for processing
      payload:
        $ref: '#/components/schemas/AgentV1_AgentV1Media'
  schemas:
    AgentV1_AgentV1ReceiveFunctionCallResponse:
      type: object
      properties:
        type:
          type: string
          enum:
            - FunctionCallResponse
          description: Message type identifier for function call responses
        id:
          type: string
          description: |
            The unique identifier for the function call.

            • **Required for client responses**: Should match the id from
              the corresponding `FunctionCallRequest`
            • **Optional for server responses**: Server may omit when responding
              to internal function executions
        name:
          type: string
          description: The name of the function being called
        content:
          type: string
          description: The content or result of the function call
      required:
        - type
        - name
        - content
      description: |
        Function call response message used bidirectionally:

        • **Client → Server**: Response after client executes a function
          marked as client_side: true
        • **Server → Client**: Response after server executes a function
          marked as client_side: false

        The same message structure serves both directions, enabling a unified
        interface for function call responses regardless of execution location.
      title: AgentV1_AgentV1ReceiveFunctionCallResponse
    AgentV1_AgentV1PromptUpdated:
      type: object
      properties:
        type:
          type: string
          enum:
            - PromptUpdated
          description: Message type identifier for prompt update confirmation
      required:
        - type
      title: AgentV1_AgentV1PromptUpdated
    AgentV1_AgentV1SpeakUpdated:
      type: object
      properties:
        type:
          type: string
          enum:
            - SpeakUpdated
          description: Message type identifier for speak update confirmation
      required:
        - type
      title: AgentV1_AgentV1SpeakUpdated
    AgentV1_AgentV1ThinkUpdated:
      type: object
      properties:
        type:
          type: string
          enum:
            - ThinkUpdated
          description: Message type identifier for think update confirmation
      required:
        - type
      title: AgentV1_AgentV1ThinkUpdated
    AgentV1_AgentV1InjectionRefused:
      type: object
      properties:
        type:
          type: string
          enum:
            - InjectionRefused
          description: Message type identifier for injection refused
        message:
          type: string
          description: Details about why the injection was refused
      required:
        - type
        - message
      title: AgentV1_AgentV1InjectionRefused
    AgentV1_AgentV1Welcome:
      type: object
      properties:
        type:
          type: string
          enum:
            - Welcome
          description: Message type identifier for welcome message
        request_id:
          type: string
          description: Unique identifier for the request
      required:
        - type
        - request_id
      title: AgentV1_AgentV1Welcome
    AgentV1_AgentV1SettingsApplied:
      type: object
      properties:
        type:
          type: string
          enum:
            - SettingsApplied
          description: Message type identifier for settings applied confirmation
      required:
        - type
      title: AgentV1_AgentV1SettingsApplied
    ChannelsAgentV1MessagesAgentV1ConversationTextRole:
      type: string
      enum:
        - user
        - assistant
      description: Identifies who spoke the statement
      title: ChannelsAgentV1MessagesAgentV1ConversationTextRole
    AgentV1_AgentV1ConversationText:
      type: object
      properties:
        type:
          type: string
          enum:
            - ConversationText
          description: Message type identifier for conversation text
        role:
          $ref: >-
            #/components/schemas/ChannelsAgentV1MessagesAgentV1ConversationTextRole
          description: Identifies who spoke the statement
        content:
          type: string
          description: The actual statement that was spoken
        languages_hinted:
          type: array
          items:
            type: string
          description: >-
            The language hints that were active at the time of the turn. Only
            present on user-role messages when the listen model is
            flux-general-multi.
        languages:
          type: array
          items:
            type: string
          description: >-
            Languages detected in the user's speech, sorted by word count
            (descending). Only present on user-role messages when the listen
            model is flux-general-multi.
      required:
        - type
        - role
        - content
      title: AgentV1_AgentV1ConversationText
    AgentV1_AgentV1UserStartedSpeaking:
      type: object
      properties:
        type:
          type: string
          enum:
            - UserStartedSpeaking
          description: Message type identifier indicating that the user has begun speaking
      required:
        - type
      title: AgentV1_AgentV1UserStartedSpeaking
    AgentV1_AgentV1AgentThinking:
      type: object
      properties:
        type:
          type: string
          enum:
            - AgentThinking
          description: Message type identifier for agent thinking
        content:
          type: string
          description: The text of the agent's thought process
      required:
        - type
        - content
      title: AgentV1_AgentV1AgentThinking
    ChannelsAgentV1MessagesAgentV1FunctionCallRequestFunctionsItems:
      type: object
      properties:
        id:
          type: string
          description: Unique identifier for the function call
        name:
          type: string
          description: The name of the function to call
        arguments:
          type: string
          description: JSON string containing the function arguments
        client_side:
          type: boolean
          description: Whether the function should be executed client-side
        thought_signature:
          type: string
          description: >-
            Some Gemini models require this as an additional function call
            identifier
      required:
        - id
        - name
        - arguments
        - client_side
      title: ChannelsAgentV1MessagesAgentV1FunctionCallRequestFunctionsItems
    AgentV1_AgentV1FunctionCallRequest:
      type: object
      properties:
        type:
          type: string
          enum:
            - FunctionCallRequest
          description: Message type identifier for function call requests
        functions:
          type: array
          items:
            $ref: >-
              #/components/schemas/ChannelsAgentV1MessagesAgentV1FunctionCallRequestFunctionsItems
          description: Array of functions to be called
      required:
        - type
        - functions
      title: AgentV1_AgentV1FunctionCallRequest
    AgentV1_AgentV1AgentStartedSpeaking:
      type: object
      properties:
        type:
          type: string
          enum:
            - AgentStartedSpeaking
          description: Message type identifier for agent started speaking
        total_latency:
          type: number
          format: double
          description: >-
            Seconds from receiving the user's utterance to producing the agent's
            reply
        tts_latency:
          type: number
          format: double
          description: The portion of total latency attributable to text-to-speech
        ttt_latency:
          type: number
          format: double
          description: >-
            The portion of total latency attributable to text-to-text (usually
            an LLM)
      required:
        - type
        - total_latency
        - tts_latency
        - ttt_latency
      title: AgentV1_AgentV1AgentStartedSpeaking
    AgentV1_AgentV1AgentAudioDone:
      type: object
      properties:
        type:
          type: string
          enum:
            - AgentAudioDone
          description: >-
            Message type identifier indicating the agent has finished sending
            audio
      required:
        - type
      title: AgentV1_AgentV1AgentAudioDone
    ChannelsAgentV1MessagesAgentV1ErrorType:
      type: string
      enum:
        - Error
      description: Message type identifier for error responses
      title: ChannelsAgentV1MessagesAgentV1ErrorType
    AgentV1_AgentV1Error:
      type: object
      properties:
        type:
          $ref: '#/components/schemas/ChannelsAgentV1MessagesAgentV1ErrorType'
          description: Message type identifier for error responses
        description:
          type: string
          description: A description of what went wrong
        code:
          type: string
          description: Error code identifying the type of error
      required:
        - type
        - description
        - code
      title: AgentV1_AgentV1Error
    ChannelsAgentV1MessagesAgentV1WarningType:
      type: string
      enum:
        - Warning
      description: Message type identifier for warnings
      title: ChannelsAgentV1MessagesAgentV1WarningType
    AgentV1_AgentV1Warning:
      type: object
      properties:
        type:
          $ref: '#/components/schemas/ChannelsAgentV1MessagesAgentV1WarningType'
          description: Message type identifier for warnings
        description:
          type: string
          description: Description of the warning
        code:
          type: string
          description: Warning code identifier
      required:
        - type
        - description
        - code
      description: Notifies the client of non-fatal errors or warnings
      title: AgentV1_AgentV1Warning
    ChannelsAgentV1MessagesAgentV1HistoryOneOf0Role:
      type: string
      enum:
        - user
        - assistant
      description: Identifies who spoke the statement
      title: ChannelsAgentV1MessagesAgentV1HistoryOneOf0Role
    AgentV1AgentV1History0:
      type: object
      properties:
        type:
          type: string
          enum:
            - History
          description: Message type identifier for conversation text
        role:
          $ref: '#/components/schemas/ChannelsAgentV1MessagesAgentV1HistoryOneOf0Role'
          description: Identifies who spoke the statement
        content:
          type: string
          description: The actual statement that was spoken
      required:
        - type
        - role
        - content
      description: Conversation text as part of the conversation history
      title: AgentV1AgentV1History0
    ChannelsAgentV1MessagesAgentV1HistoryOneOf1FunctionCallsItems:
      type: object
      properties:
        id:
          type: string
          description: Unique identifier for the function call
        name:
          type: string
          description: Name of the function called
        client_side:
          type: boolean
          description: Indicates if the call was client-side or server-side
        arguments:
          type: string
          description: Arguments passed to the function
        response:
          type: string
          description: Response from the function call
        thought_signature:
          type: string
          description: >-
            Some Gemini models require this as an additional function call
            identifier
      required:
        - id
        - name
        - client_side
        - arguments
        - response
      title: ChannelsAgentV1MessagesAgentV1HistoryOneOf1FunctionCallsItems
    AgentV1AgentV1History1:
      type: object
      properties:
        type:
          type: string
          enum:
            - History
        function_calls:
          type: array
          items:
            $ref: >-
              #/components/schemas/ChannelsAgentV1MessagesAgentV1HistoryOneOf1FunctionCallsItems
          description: List of function call objects
      required:
        - type
        - function_calls
      description: >-
        Client-side or server-side function call request and response as part of
        the conversation history
      title: AgentV1AgentV1History1
    AgentV1_AgentV1History:
      oneOf:
        - $ref: '#/components/schemas/AgentV1AgentV1History0'
        - $ref: '#/components/schemas/AgentV1AgentV1History1'
      description: A history message is either a conversational message or a function call
      title: AgentV1_AgentV1History
    AgentV1_AgentV1Audio:
      type: string
      format: binary
      title: AgentV1_AgentV1Audio
    ChannelsAgentV1MessagesAgentV1SettingsFlags:
      type: object
      properties:
        history:
          type: boolean
          default: true
          description: Enable or disable history message reporting
      title: ChannelsAgentV1MessagesAgentV1SettingsFlags
    ChannelsAgentV1MessagesAgentV1SettingsAudioInputEncoding:
      type: string
      enum:
        - linear16
        - linear32
        - flac
        - alaw
        - mulaw
        - amr-nb
        - amr-wb
        - opus
        - ogg-opus
        - speex
        - g729
      default: linear16
      description: Audio encoding format
      title: ChannelsAgentV1MessagesAgentV1SettingsAudioInputEncoding
    ChannelsAgentV1MessagesAgentV1SettingsAudioInput:
      type: object
      properties:
        encoding:
          $ref: >-
            #/components/schemas/ChannelsAgentV1MessagesAgentV1SettingsAudioInputEncoding
          description: Audio encoding format
        sample_rate:
          type: integer
          default: 24000
          description: Sample rate in Hz. Common values are 16000, 24000, 44100, 48000
      required:
        - encoding
        - sample_rate
      description: >-
        Audio input configuration settings. If omitted, defaults to
        encoding=linear16 and sample_rate=24000. Higher sample rates like 44100
        Hz provide better audio quality.
      title: ChannelsAgentV1MessagesAgentV1SettingsAudioInput
    ChannelsAgentV1MessagesAgentV1SettingsAudioOutputEncoding:
      type: string
      enum:
        - linear16
        - mulaw
        - alaw
        - mp3
        - opus
        - flac
        - aac
      default: linear16
      description: Audio encoding format for streaming TTS output
      title: ChannelsAgentV1MessagesAgentV1SettingsAudioOutputEncoding
    ChannelsAgentV1MessagesAgentV1SettingsAudioOutputContainer:
      type: string
      enum:
        - none
        - wav
        - ogg
      default: none
      description: Audio container format.
      title: ChannelsAgentV1MessagesAgentV1SettingsAudioOutputContainer
    ChannelsAgentV1MessagesAgentV1SettingsAudioOutput:
      type: object
      properties:
        encoding:
          $ref: >-
            #/components/schemas/ChannelsAgentV1MessagesAgentV1SettingsAudioOutputEncoding
          description: Audio encoding format for streaming TTS output
        sample_rate:
          type: integer
          description: Sample rate in Hz
        bitrate:
          type: integer
          description: Audio bitrate in bits per second
        container:
          $ref: >-
            #/components/schemas/ChannelsAgentV1MessagesAgentV1SettingsAudioOutputContainer
          description: Audio container format.
      description: Audio output configuration settings
      title: ChannelsAgentV1MessagesAgentV1SettingsAudioOutput
    ChannelsAgentV1MessagesAgentV1SettingsAudio:
      type: object
      properties:
        input:
          $ref: >-
            #/components/schemas/ChannelsAgentV1MessagesAgentV1SettingsAudioInput
          description: >-
            Audio input configuration settings. If omitted, defaults to
            encoding=linear16 and sample_rate=24000. Higher sample rates like
            44100 Hz provide better audio quality.
        output:
          $ref: >-
            #/components/schemas/ChannelsAgentV1MessagesAgentV1SettingsAudioOutput
          description: Audio output configuration settings
      title: ChannelsAgentV1MessagesAgentV1SettingsAudio
    ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0ContextMessagesItemsOneOf0Role:
      type: string
      enum:
        - user
        - assistant
      description: Identifies who spoke the statement
      title: >-
        ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0ContextMessagesItemsOneOf0Role
    ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0ContextMessagesItems0:
      type: object
      properties:
        type:
          type: string
          enum:
            - History
          description: Message type identifier for conversation text
        role:
          $ref: >-
            #/components/schemas/ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0ContextMessagesItemsOneOf0Role
          description: Identifies who spoke the statement
        content:
          type: string
          description: The actual statement that was spoken
      required:
        - type
        - role
        - content
      description: Conversation text as part of the conversation history
      title: ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0ContextMessagesItems0
    ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0ContextMessagesItemsOneOf1FunctionCallsItems:
      type: object
      properties:
        id:
          type: string
          description: Unique identifier for the function call
        name:
          type: string
          description: Name of the function called
        client_side:
          type: boolean
          description: Indicates if the call was client-side or server-side
        arguments:
          type: string
          description: Arguments passed to the function
        response:
          type: string
          description: Response from the function call
        thought_signature:
          type: string
          description: >-
            Some Gemini models require this as an additional function call
            identifier
      required:
        - id
        - name
        - client_side
        - arguments
        - response
      title: >-
        ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0ContextMessagesItemsOneOf1FunctionCallsItems
    ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0ContextMessagesItems1:
      type: object
      properties:
        type:
          type: string
          enum:
            - History
        function_calls:
          type: array
          items:
            $ref: >-
              #/components/schemas/ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0ContextMessagesItemsOneOf1FunctionCallsItems
          description: List of function call objects
      required:
        - type
        - function_calls
      description: >-
        Client-side or server-side function call request and response as part of
        the conversation history
      title: ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0ContextMessagesItems1
    ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0ContextMessagesItems:
      oneOf:
        - $ref: >-
            #/components/schemas/ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0ContextMessagesItems0
        - $ref: >-
            #/components/schemas/ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0ContextMessagesItems1
      description: A history message is either a conversational message or a function call
      title: ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0ContextMessagesItems
    ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0Context:
      type: object
      properties:
        messages:
          type: array
          items:
            $ref: >-
              #/components/schemas/ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0ContextMessagesItems
          description: Conversation history as a list of messages and function calls
      description: >-
        Conversation context including the history of messages and function
        calls
      title: ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0Context
    DeepgramListenProviderV1:
      type: object
      properties:
        type:
          type: string
          enum:
            - deepgram
          description: Provider type for speech-to-text
        version:
          type: string
          enum:
            - v1
          description: Specifies usage of the V1 Deepgram speech-to-text API
        model:
          type: string
          description: >-
            Model to use for speech to text using the V1 API (e.g. Nova-3,
            Nova-2)
        language:
          type: string
          default: en-US
          description: >-
            Language code to use for speech-to-text. Can be a BCP-47 language
            tag (e.g. `en`), or `multi` for code-switching transcription
        keyterms:
          type: array
          items:
            type: string
          description: Prompt keyterm recognition to improve Keyword Recall Rate
        smart_format:
          type: boolean
          default: false
          description: Applies smart formatting to improve transcript readability
      required:
        - type
      title: DeepgramListenProviderV1
    DeepgramListenProviderV2:
      type: object
      properties:
        type:
          type: string
          enum:
            - deepgram
          description: Provider type for speech-to-text
        version:
          type: string
          enum:
            - v2
          description: Specifies usage of the V2 Deepgram speech-to-text API (e.g. Flux)
        model:
          type: string
          description: >-
            Model to use for speech to text using the V2 API (e.g.
            flux-general-en, flux-general-multi)
        language_hints:
          type: array
          items:
            type: string
          description: >-
            An array of one or more BCP-47 language codes to bias the model
            toward specific languages. Only supported when model is
            flux-general-multi. Without hints, the model auto-detects the spoken
            language. See the Language Prompting guide for details.
        keyterms:
          type: array
          items:
            type: string
          description: Prompt keyterm recognition to improve Keyword Recall Rate
      required:
        - type
        - model
      title: DeepgramListenProviderV2
    ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0ListenProvider:
      oneOf:
        - $ref: '#/components/schemas/DeepgramListenProviderV1'
        - $ref: '#/components/schemas/DeepgramListenProviderV2'
      title: ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0ListenProvider
    ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0Listen:
      type: object
      properties:
        provider:
          $ref: >-
            #/components/schemas/ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0ListenProvider
      title: ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0Listen
    OpenAiThinkProviderVersion:
      type: string
      enum:
        - v1
      description: The REST API version for the OpenAI chat completions API
      title: OpenAiThinkProviderVersion
    OpenAiThinkProviderModel:
      type: string
      enum:
        - gpt-5
        - gpt-5-mini
        - gpt-5-nano
        - gpt-4.1
        - gpt-4.1-mini
        - gpt-4.1-nano
        - gpt-4o
        - gpt-4o-mini
      description: OpenAI model to use
      title: OpenAiThinkProviderModel
    OpenAiThinkProviderReasoningMode:
      type: string
      enum:
        - none
        - minimal
        - low
        - medium
        - high
      description: OpenAI reasoning_effort
      title: OpenAiThinkProviderReasoningMode
    OpenAiThinkProvider:
      type: object
      properties:
        type:
          type: string
          enum:
            - open_ai
        version:
          $ref: '#/components/schemas/OpenAiThinkProviderVersion'
          description: The REST API version for the OpenAI chat completions API
        model:
          $ref: '#/components/schemas/OpenAiThinkProviderModel'
          description: OpenAI model to use
        temperature:
          type: number
          format: double
          description: OpenAI temperature (0-2)
        reasoning_mode:
          $ref: '#/components/schemas/OpenAiThinkProviderReasoningMode'
          description: OpenAI reasoning_effort
      required:
        - type
        - model
      title: OpenAiThinkProvider
    AwsBedrockThinkProviderModel:
      type: string
      enum:
        - anthropic/claude-3-5-sonnet-20240620-v1:0
        - anthropic/claude-3-5-haiku-20240307-v1:0
      description: AWS Bedrock model to use
      title: AwsBedrockThinkProviderModel
    AwsBedrockThinkProviderCredentialsType:
      type: string
      enum:
        - sts
        - iam
      description: AWS credentials type (STS short-lived or IAM long-lived)
      title: AwsBedrockThinkProviderCredentialsType
    AwsBedrockThinkProviderCredentials:
      type: object
      properties:
        type:
          $ref: '#/components/schemas/AwsBedrockThinkProviderCredentialsType'
          description: AWS credentials type (STS short-lived or IAM long-lived)
        region:
          type: string
          description: AWS region
        access_key_id:
          type: string
          description: AWS access key
        secret_access_key:
          type: string
          description: AWS secret access key
        session_token:
          type: string
          description: AWS session token (required for STS only)
      description: AWS credentials type (STS short-lived or IAM long-lived)
      title: AwsBedrockThinkProviderCredentials
    AwsBedrockThinkProvider:
      type: object
      properties:
        type:
          type: string
          enum:
            - aws_bedrock
        model:
          $ref: '#/components/schemas/AwsBedrockThinkProviderModel'
          description: AWS Bedrock model to use
        temperature:
          type: number
          format: double
          description: AWS Bedrock temperature (0-2)
        credentials:
          $ref: '#/components/schemas/AwsBedrockThinkProviderCredentials'
          description: AWS credentials type (STS short-lived or IAM long-lived)
      required:
        - type
        - model
      title: AwsBedrockThinkProvider
    AnthropicThinkProviderVersion:
      type: string
      enum:
        - v1
      description: The REST API version for the Anthropic Messages API
      title: AnthropicThinkProviderVersion
    AnthropicThinkProviderModel:
      type: string
      enum:
        - claude-3-5-haiku-latest
        - claude-sonnet-4-20250514
      description: Anthropic model to use
      title: AnthropicThinkProviderModel
    AnthropicThinkProvider:
      type: object
      properties:
        type:
          type: string
          enum:
            - anthropic
        version:
          $ref: '#/components/schemas/AnthropicThinkProviderVersion'
          description: The REST API version for the Anthropic Messages API
        model:
          $ref: '#/components/schemas/AnthropicThinkProviderModel'
          description: Anthropic model to use
        temperature:
          type: number
          format: double
          description: Anthropic temperature (0-1)
      required:
        - type
        - model
      title: AnthropicThinkProvider
    GoogleThinkProviderVersion:
      type: string
      enum:
        - v1beta
      description: The REST API version for the Google generative language API
      title: GoogleThinkProviderVersion
    GoogleThinkProviderModel:
      type: string
      enum:
        - gemini-2.0-flash
        - gemini-2.0-flash-lite
        - gemini-2.5-flash
      description: Google model to use
      title: GoogleThinkProviderModel
    GoogleThinkProvider:
      type: object
      properties:
        type:
          type: string
          enum:
            - google
        version:
          $ref: '#/components/schemas/GoogleThinkProviderVersion'
          description: The REST API version for the Google generative language API
        model:
          $ref: '#/components/schemas/GoogleThinkProviderModel'
          description: Google model to use
        temperature:
          type: number
          format: double
          description: Google temperature (0-2)
      required:
        - type
        - model
      title: GoogleThinkProvider
    GroqThinkProviderVersion:
      type: string
      enum:
        - v1
      description: >-
        The REST API version for the Groq's chat completions API (mostly
        OpenAI-compatible)
      title: GroqThinkProviderVersion
    GroqThinkProviderModel:
      type: string
      enum:
        - openai/gpt-oss-20b
      description: Groq model to use
      title: GroqThinkProviderModel
    GroqThinkProviderReasoningMode:
      type: string
      enum:
        - none
        - minimal
        - low
        - medium
        - high
      description: OpenAI reasoning_effort
      title: GroqThinkProviderReasoningMode
    GroqThinkProvider:
      type: object
      properties:
        type:
          type: string
          enum:
            - groq
        version:
          $ref: '#/components/schemas/GroqThinkProviderVersion'
          description: >-
            The REST API version for the Groq's chat completions API (mostly
            OpenAI-compatible)
        model:
          $ref: '#/components/schemas/GroqThinkProviderModel'
          description: Groq model to use
        temperature:
          type: number
          format: double
          description: Groq temperature (0-2)
        reasoning_mode:
          $ref: '#/components/schemas/GroqThinkProviderReasoningMode'
          description: OpenAI reasoning_effort
      required:
        - type
        - model
      title: GroqThinkProvider
    ThinkSettingsV1Provider:
      oneOf:
        - $ref: '#/components/schemas/OpenAiThinkProvider'
        - $ref: '#/components/schemas/AwsBedrockThinkProvider'
        - $ref: '#/components/schemas/AnthropicThinkProvider'
        - $ref: '#/components/schemas/GoogleThinkProvider'
        - $ref: '#/components/schemas/GroqThinkProvider'
      title: ThinkSettingsV1Provider
    ThinkSettingsV1Endpoint:
      type: object
      properties:
        url:
          type: string
          description: Custom LLM endpoint URL
        headers:
          type: object
          additionalProperties:
            type: string
          description: Custom headers for the endpoint
      description: >
        Optional for non-Deepgram LLM providers. When present, must include url
        field and headers object
      title: ThinkSettingsV1Endpoint
    ThinkSettingsV1FunctionsItemsParameters:
      type: object
      properties: {}
      description: Function parameters
      title: ThinkSettingsV1FunctionsItemsParameters
    ThinkSettingsV1FunctionsItemsEndpoint:
      type: object
      properties:
        url:
          type: string
          description: Endpoint URL
        method:
          type: string
          description: HTTP method
        headers:
          type: object
          additionalProperties:
            type: string
      description: >-
        The Function endpoint to call. if not passed, function is called
        client-side
      title: ThinkSettingsV1FunctionsItemsEndpoint
    ThinkSettingsV1FunctionsItems:
      type: object
      properties:
        name:
          type: string
          description: Function name
        description:
          type: string
          description: Function description
        parameters:
          $ref: '#/components/schemas/ThinkSettingsV1FunctionsItemsParameters'
          description: Function parameters
        endpoint:
          $ref: '#/components/schemas/ThinkSettingsV1FunctionsItemsEndpoint'
          description: >-
            The Function endpoint to call. if not passed, function is called
            client-side
      title: ThinkSettingsV1FunctionsItems
    ThinkSettingsV1ContextLength0:
      type: string
      enum:
        - max
      description: Agent will not discard context regardless of length
      title: ThinkSettingsV1ContextLength0
    ThinkSettingsV1ContextLength:
      oneOf:
        - $ref: '#/components/schemas/ThinkSettingsV1ContextLength0'
        - type: number
          format: double
      description: >
        Specifies the number of characters retained in context between user
        messages, agent responses, and function calls. This setting is only
        configurable when a custom think endpoint is used
      title: ThinkSettingsV1ContextLength
    ThinkSettingsV1:
      type: object
      properties:
        provider:
          $ref: '#/components/schemas/ThinkSettingsV1Provider'
        endpoint:
          $ref: '#/components/schemas/ThinkSettingsV1Endpoint'
          description: >
            Optional for non-Deepgram LLM providers. When present, must include
            url field and headers object
        functions:
          type: array
          items:
            $ref: '#/components/schemas/ThinkSettingsV1FunctionsItems'
        prompt:
          type: string
        context_length:
          $ref: '#/components/schemas/ThinkSettingsV1ContextLength'
          description: >
            Specifies the number of characters retained in context between user
            messages, agent responses, and function calls. This setting is only
            configurable when a custom think endpoint is used
      required:
        - provider
      title: ThinkSettingsV1
    ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0Think1:
      type: array
      items:
        $ref: '#/components/schemas/ThinkSettingsV1'
      title: ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0Think1
    ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0Think:
      oneOf:
        - $ref: '#/components/schemas/ThinkSettingsV1'
        - $ref: >-
            #/components/schemas/ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0Think1
      title: ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0Think
    DeepgramSpeakProviderVersion:
      type: string
      enum:
        - v1
      description: The REST API version for the Deepgram text-to-speech API
      title: DeepgramSpeakProviderVersion
    DeepgramSpeakProviderModel:
      type: string
      enum:
        - aura-asteria-en
        - aura-luna-en
        - aura-stella-en
        - aura-athena-en
        - aura-hera-en
        - aura-orion-en
        - aura-arcas-en
        - aura-perseus-en
        - aura-angus-en
        - aura-orpheus-en
        - aura-helios-en
        - aura-zeus-en
        - aura-2-amalthea-en
        - aura-2-andromeda-en
        - aura-2-apollo-en
        - aura-2-arcas-en
        - aura-2-aries-en
        - aura-2-asteria-en
        - aura-2-athena-en
        - aura-2-atlas-en
        - aura-2-aurora-en
        - aura-2-callista-en
        - aura-2-cora-en
        - aura-2-cordelia-en
        - aura-2-delia-en
        - aura-2-draco-en
        - aura-2-electra-en
        - aura-2-harmonia-en
        - aura-2-helena-en
        - aura-2-hera-en
        - aura-2-hermes-en
        - aura-2-hyperion-en
        - aura-2-iris-en
        - aura-2-janus-en
        - aura-2-juno-en
        - aura-2-jupiter-en
        - aura-2-luna-en
        - aura-2-mars-en
        - aura-2-minerva-en
        - aura-2-neptune-en
        - aura-2-odysseus-en
        - aura-2-ophelia-en
        - aura-2-orion-en
        - aura-2-orpheus-en
        - aura-2-pandora-en
        - aura-2-phoebe-en
        - aura-2-pluto-en
        - aura-2-saturn-en
        - aura-2-selene-en
        - aura-2-thalia-en
        - aura-2-theia-en
        - aura-2-vesta-en
        - aura-2-zeus-en
        - aura-2-sirio-es
        - aura-2-nestor-es
        - aura-2-carina-es
        - aura-2-celeste-es
        - aura-2-alvaro-es
        - aura-2-diana-es
        - aura-2-aquila-es
        - aura-2-selena-es
        - aura-2-estrella-es
        - aura-2-javier-es
      description: Deepgram TTS model
      title: DeepgramSpeakProviderModel
    DeepgramSpeakProvider:
      type: object
      properties:
        type:
          type: string
          enum:
            - deepgram
        version:
          $ref: '#/components/schemas/DeepgramSpeakProviderVersion'
          description: The REST API version for the Deepgram text-to-speech API
        model:
          $ref: '#/components/schemas/DeepgramSpeakProviderModel'
          description: Deepgram TTS model
        speed:
          type: number
          format: double
          default: 1
          description: >-
            Speaking rate multiplier that adjusts the pace of generated speech
            while preserving natural prosody and voice quality. Not yet
            supported in all languages.
      required:
        - type
        - model
      title: DeepgramSpeakProvider
    ElevenLabsSpeakProviderVersion:
      type: string
      enum:
        - v1
      description: The REST API version for the ElevenLabs text-to-speech API
      title: ElevenLabsSpeakProviderVersion
    ElevenLabsSpeakProviderModelId:
      type: string
      enum:
        - eleven_turbo_v2_5
        - eleven_monolingual_v1
        - eleven_multilingual_v2
      description: Eleven Labs model ID
      title: ElevenLabsSpeakProviderModelId
    ElevenLabsSpeakProvider:
      type: object
      properties:
        type:
          type: string
          enum:
            - eleven_labs
        version:
          $ref: '#/components/schemas/ElevenLabsSpeakProviderVersion'
          description: The REST API version for the ElevenLabs text-to-speech API
        model_id:
          $ref: '#/components/schemas/ElevenLabsSpeakProviderModelId'
          description: Eleven Labs model ID
        language:
          type: string
          description: >-
            Optional language to use, e.g. 'en-US'. Corresponds to the
            `language_code` parameter in the ElevenLabs API
        language_code:
          type: string
          description: Use the `language` field instead.
      required:
        - type
        - model_id
      title: ElevenLabsSpeakProvider
    CartesiaSpeakProviderVersion:
      type: string
      enum:
        - '2025-03-17'
      description: The API version header for the Cartesia text-to-speech API
      title: CartesiaSpeakProviderVersion
    CartesiaSpeakProviderModelId:
      type: string
      enum:
        - sonic-2
        - sonic-multilingual
      description: Cartesia model ID
      title: CartesiaSpeakProviderModelId
    CartesiaSpeakProviderVoice:
      type: object
      properties:
        mode:
          type: string
          description: Cartesia voice mode
        id:
          type: string
          description: Cartesia voice ID
      required:
        - mode
        - id
      title: CartesiaSpeakProviderVoice
    CartesiaSpeakProvider:
      type: object
      properties:
        type:
          type: string
          enum:
            - cartesia
        version:
          $ref: '#/components/schemas/CartesiaSpeakProviderVersion'
          description: The API version header for the Cartesia text-to-speech API
        model_id:
          $ref: '#/components/schemas/CartesiaSpeakProviderModelId'
          description: Cartesia model ID
        voice:
          $ref: '#/components/schemas/CartesiaSpeakProviderVoice'
        language:
          type: string
          description: Cartesia language code
        volume:
          type: number
          format: double
          description: >
            Volume level for Cartesia TTS output. Valid range: 0.5 to 2.0. See
            [Cartesia
            documentation](https://docs.cartesia.ai/build-with-cartesia/sonic-3/volume-speed-emotion#volume-speed-and-emotion).
      required:
        - type
        - model_id
        - voice
      title: CartesiaSpeakProvider
    OpenAiSpeakProviderVersion:
      type: string
      enum:
        - v1
      description: The REST API version for the OpenAI text-to-speech API
      title: OpenAiSpeakProviderVersion
    OpenAiSpeakProviderModel:
      type: string
      enum:
        - tts-1
        - tts-1-hd
      description: OpenAI TTS model
      title: OpenAiSpeakProviderModel
    OpenAiSpeakProviderVoice:
      type: string
      enum:
        - alloy
        - echo
        - fable
        - onyx
        - nova
        - shimmer
      description: OpenAI voice
      title: OpenAiSpeakProviderVoice
    OpenAiSpeakProvider:
      type: object
      properties:
        type:
          type: string
          enum:
            - open_ai
        version:
          $ref: '#/components/schemas/OpenAiSpeakProviderVersion'
          description: The REST API version for the OpenAI text-to-speech API
        model:
          $ref: '#/components/schemas/OpenAiSpeakProviderModel'
          description: OpenAI TTS model
        voice:
          $ref: '#/components/schemas/OpenAiSpeakProviderVoice'
          description: OpenAI voice
      required:
        - type
        - model
        - voice
      title: OpenAiSpeakProvider
    AwsPollySpeakProviderVoice:
      type: string
      enum:
        - Matthew
        - Joanna
        - Amy
        - Emma
        - Brian
        - Arthur
        - Aria
        - Ayanda
      description: AWS Polly voice name
      title: AwsPollySpeakProviderVoice
    AwsPollySpeakProviderEngine:
      type: string
      enum:
        - generative
        - long-form
        - standard
        - neural
      title: AwsPollySpeakProviderEngine
    AwsPollySpeakProviderCredentialsType:
      type: string
      enum:
        - sts
        - iam
      title: AwsPollySpeakProviderCredentialsType
    AwsPollySpeakProviderCredentials:
      type: object
      properties:
        type:
          $ref: '#/components/schemas/AwsPollySpeakProviderCredentialsType'
        region:
          type: string
        access_key_id:
          type: string
        secret_access_key:
          type: string
        session_token:
          type: string
          description: Required for STS only
      required:
        - type
        - region
        - access_key_id
        - secret_access_key
      title: AwsPollySpeakProviderCredentials
    AwsPollySpeakProvider:
      type: object
      properties:
        type:
          type: string
          enum:
            - aws_polly
        voice:
          $ref: '#/components/schemas/AwsPollySpeakProviderVoice'
          description: AWS Polly voice name
        language:
          type: string
          description: >-
            Language code to use, e.g. 'en-US'. Corresponds to the
            `language_code` parameter in the AWS Polly API
        language_code:
          type: string
          description: Use the `language` field instead.
        engine:
          $ref: '#/components/schemas/AwsPollySpeakProviderEngine'
        credentials:
          $ref: '#/components/schemas/AwsPollySpeakProviderCredentials'
      required:
        - type
        - voice
        - language
        - engine
        - credentials
      title: AwsPollySpeakProvider
    SpeakSettingsV1Provider:
      oneOf:
        - $ref: '#/components/schemas/DeepgramSpeakProvider'
        - $ref: '#/components/schemas/ElevenLabsSpeakProvider'
        - $ref: '#/components/schemas/CartesiaSpeakProvider'
        - $ref: '#/components/schemas/OpenAiSpeakProvider'
        - $ref: '#/components/schemas/AwsPollySpeakProvider'
      title: SpeakSettingsV1Provider
    SpeakSettingsV1Endpoint:
      type: object
      properties:
        url:
          type: string
          description: >
            Custom TTS endpoint URL. Cannot contain `output_format` or
            `model_id` query parameters when the provider is Eleven Labs.
        headers:
          type: object
          additionalProperties:
            type: string
      description: >
        Optional if provider is Deepgram. Required for non-Deepgram TTS
        providers.

        When present, must include url field and headers object. Valid schemes
        are https and wss with wss only supported for Eleven Labs.
      title: SpeakSettingsV1Endpoint
    SpeakSettingsV1:
      type: object
      properties:
        provider:
          $ref: '#/components/schemas/SpeakSettingsV1Provider'
        endpoint:
          $ref: '#/components/schemas/SpeakSettingsV1Endpoint'
          description: >
            Optional if provider is Deepgram. Required for non-Deepgram TTS
            providers.

            When present, must include url field and headers object. Valid
            schemes are https and wss with wss only supported for Eleven Labs.
      required:
        - provider
      title: SpeakSettingsV1
    ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0Speak1:
      type: array
      items:
        $ref: '#/components/schemas/SpeakSettingsV1'
      title: ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0Speak1
    ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0Speak:
      oneOf:
        - $ref: '#/components/schemas/SpeakSettingsV1'
        - $ref: >-
            #/components/schemas/ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0Speak1
      title: ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0Speak
    ChannelsAgentV1MessagesAgentV1SettingsAgent0:
      type: object
      properties:
        language:
          type: string
          default: en
          description: >-
            Deprecated. Use `listen.provider.language` and
            `speak.provider.language` fields instead.
        context:
          $ref: >-
            #/components/schemas/ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0Context
          description: >-
            Conversation context including the history of messages and function
            calls
        listen:
          $ref: >-
            #/components/schemas/ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0Listen
        think:
          $ref: >-
            #/components/schemas/ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0Think
        speak:
          $ref: >-
            #/components/schemas/ChannelsAgentV1MessagesAgentV1SettingsAgentOneOf0Speak
        greeting:
          type: string
          description: Optional message that agent will speak at the start
      title: ChannelsAgentV1MessagesAgentV1SettingsAgent0
    ChannelsAgentV1MessagesAgentV1SettingsAgent:
      oneOf:
        - $ref: '#/components/schemas/ChannelsAgentV1MessagesAgentV1SettingsAgent0'
        - type: string
          format: uuid
      title: ChannelsAgentV1MessagesAgentV1SettingsAgent
    AgentV1_AgentV1Settings:
      type: object
      properties:
        type:
          type: string
          enum:
            - Settings
        tags:
          type: array
          items:
            type: string
          description: Tags to associate with the request
        experimental:
          type: boolean
          default: false
          description: To enable experimental features
        flags:
          $ref: '#/components/schemas/ChannelsAgentV1MessagesAgentV1SettingsFlags'
        mip_opt_out:
          type: boolean
          default: false
          description: To opt out of Deepgram Model Improvement Program
        audio:
          $ref: '#/components/schemas/ChannelsAgentV1MessagesAgentV1SettingsAudio'
        agent:
          $ref: '#/components/schemas/ChannelsAgentV1MessagesAgentV1SettingsAgent'
      required:
        - type
        - audio
        - agent
      title: AgentV1_AgentV1Settings
    ChannelsAgentV1MessagesAgentV1UpdateSpeakSpeak1:
      type: array
      items:
        $ref: '#/components/schemas/SpeakSettingsV1'
      title: ChannelsAgentV1MessagesAgentV1UpdateSpeakSpeak1
    ChannelsAgentV1MessagesAgentV1UpdateSpeakSpeak:
      oneOf:
        - $ref: '#/components/schemas/SpeakSettingsV1'
        - $ref: '#/components/schemas/ChannelsAgentV1MessagesAgentV1UpdateSpeakSpeak1'
      title: ChannelsAgentV1MessagesAgentV1UpdateSpeakSpeak
    AgentV1_AgentV1UpdateSpeak:
      type: object
      properties:
        type:
          type: string
          enum:
            - UpdateSpeak
          description: Message type identifier for updating the speak model
        speak:
          $ref: '#/components/schemas/ChannelsAgentV1MessagesAgentV1UpdateSpeakSpeak'
      required:
        - type
        - speak
      title: AgentV1_AgentV1UpdateSpeak
    AgentV1_AgentV1InjectUserMessage:
      type: object
      properties:
        type:
          type: string
          enum:
            - InjectUserMessage
          description: Message type identifier for injecting a user message
        content:
          type: string
          description: The specific phrase or statement the agent should respond to
      required:
        - type
        - content
      title: AgentV1_AgentV1InjectUserMessage
    ChannelsAgentV1MessagesAgentV1InjectAgentMessageBehavior:
      type: string
      enum:
        - default
        - queue
      default: default
      description: >
        Controls how the injection interacts with any in-progress user or agent
        turn.


        * `default` — The agent speaks only if neither the user nor the agent is
        mid-turn. If a turn is in progress, the server replies with
        `InjectionRefused`.

        * `queue` — The message is appended after any already-queued
        `ConversationText` without interrupting the current agent turn or think
        response. If nothing is queued, the message plays immediately.
      title: ChannelsAgentV1MessagesAgentV1InjectAgentMessageBehavior
    AgentV1_AgentV1InjectAgentMessage:
      type: object
      properties:
        type:
          type: string
          enum:
            - InjectAgentMessage
          description: Message type identifier for injecting an agent message
        message:
          type: string
          description: The statement that the agent should say
        behavior:
          $ref: >-
            #/components/schemas/ChannelsAgentV1MessagesAgentV1InjectAgentMessageBehavior
          description: >
            Controls how the injection interacts with any in-progress user or
            agent turn.


            * `default` — The agent speaks only if neither the user nor the
            agent is mid-turn. If a turn is in progress, the server replies with
            `InjectionRefused`.

            * `queue` — The message is appended after any already-queued
            `ConversationText` without interrupting the current agent turn or
            think response. If nothing is queued, the message plays immediately.
      required:
        - type
        - message
      title: AgentV1_AgentV1InjectAgentMessage
    AgentV1_AgentV1SendFunctionCallResponse:
      type: object
      properties:
        type:
          type: string
          enum:
            - FunctionCallResponse
          description: Message type identifier for function call responses
        id:
          type: string
          description: |
            The unique identifier for the function call.

            • **Required for client responses**: Should match the id from
              the corresponding `FunctionCallRequest`
            • **Optional for server responses**: Server may omit when responding
              to internal function executions
        name:
          type: string
          description: The name of the function being called
        content:
          type: string
          description: The content or result of the function call
      required:
        - type
        - name
        - content
      description: |
        Function call response message used bidirectionally:

        • **Client → Server**: Response after client executes a function
          marked as client_side: true
        • **Server → Client**: Response after server executes a function
          marked as client_side: false

        The same message structure serves both directions, enabling a unified
        interface for function call responses regardless of execution location.
      title: AgentV1_AgentV1SendFunctionCallResponse
    ChannelsAgentV1MessagesAgentV1KeepAliveType:
      type: string
      enum:
        - KeepAlive
      description: Message type identifier
      title: ChannelsAgentV1MessagesAgentV1KeepAliveType
    AgentV1_AgentV1KeepAlive:
      type: object
      properties:
        type:
          $ref: '#/components/schemas/ChannelsAgentV1MessagesAgentV1KeepAliveType'
          description: Message type identifier
      required:
        - type
      description: Send a control message to the agent
      title: AgentV1_AgentV1KeepAlive
    AgentV1_AgentV1UpdatePrompt:
      type: object
      properties:
        type:
          type: string
          enum:
            - UpdatePrompt
          description: Message type identifier for prompt update request
        prompt:
          type: string
          description: The new system prompt to be used by the agent
      required:
        - type
        - prompt
      title: AgentV1_AgentV1UpdatePrompt
    ChannelsAgentV1MessagesAgentV1UpdateThinkThink1:
      type: array
      items:
        $ref: '#/components/schemas/ThinkSettingsV1'
      title: ChannelsAgentV1MessagesAgentV1UpdateThinkThink1
    ChannelsAgentV1MessagesAgentV1UpdateThinkThink:
      oneOf:
        - $ref: '#/components/schemas/ThinkSettingsV1'
        - $ref: '#/components/schemas/ChannelsAgentV1MessagesAgentV1UpdateThinkThink1'
      title: ChannelsAgentV1MessagesAgentV1UpdateThinkThink
    AgentV1_AgentV1UpdateThink:
      type: object
      properties:
        type:
          type: string
          enum:
            - UpdateThink
          description: Message type identifier for updating the think model
        think:
          $ref: '#/components/schemas/ChannelsAgentV1MessagesAgentV1UpdateThinkThink'
      required:
        - type
        - think
      title: AgentV1_AgentV1UpdateThink
    AgentV1_AgentV1Media:
      type: string
      format: binary
      title: AgentV1_AgentV1Media

```
# Think Models

GET https://agent.deepgram.com/v1/agent/settings/think/models

Retrieves the available think models that can be used for AI agent processing

Reference: https://developers.deepgram.com/reference/voice-agent/think-models

## OpenAPI Specification

```yaml
openapi: 3.1.0
info:
  title: Deepgram API Specification
  version: 1.0.0
paths:
  /v1/agent/settings/think/models:
    get:
      operationId: list
      summary: List Agent Think Models
      description: >-
        Retrieves the available think models that can be used for AI agent
        processing
      tags:
        - >-
          subpackage_agent.subpackage_agent/v1.subpackage_agent/v1/settings.subpackage_agent/v1/settings/think.subpackage_agent/v1/settings/think/models
      responses:
        '200':
          description: List of available think models
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/AgentThinkModelsV1Response'
        '400':
          description: Invalid Request
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/ErrorResponse'
servers:
  - url: https://agent.deepgram.com
components:
  schemas:
    AgentThinkModelsV1ResponseModelsItemsOneOf0Id:
      type: string
      enum:
        - gpt-5
        - gpt-5-mini
        - gpt-5-nano
        - gpt-4.1
        - gpt-4.1-mini
        - gpt-4.1-nano
        - gpt-4o
        - gpt-4o-mini
      description: The unique identifier of the OpenAI model
      title: AgentThinkModelsV1ResponseModelsItemsOneOf0Id
    AgentThinkModelsV1ResponseModelsItems0:
      type: object
      properties:
        id:
          $ref: '#/components/schemas/AgentThinkModelsV1ResponseModelsItemsOneOf0Id'
          description: The unique identifier of the OpenAI model
        name:
          type: string
          description: The display name of the model
        provider:
          description: The provider of the model
      required:
        - id
        - name
        - provider
      description: OpenAI models
      title: AgentThinkModelsV1ResponseModelsItems0
    AgentThinkModelsV1ResponseModelsItemsOneOf1Id:
      type: string
      enum:
        - claude-3-5-haiku-latest
        - claude-sonnet-4-20250514
      description: The unique identifier of the Anthropic model
      title: AgentThinkModelsV1ResponseModelsItemsOneOf1Id
    AgentThinkModelsV1ResponseModelsItems1:
      type: object
      properties:
        id:
          $ref: '#/components/schemas/AgentThinkModelsV1ResponseModelsItemsOneOf1Id'
          description: The unique identifier of the Anthropic model
        name:
          type: string
          description: The display name of the model
        provider:
          description: The provider of the model
      required:
        - id
        - name
        - provider
      description: Anthropic models
      title: AgentThinkModelsV1ResponseModelsItems1
    AgentThinkModelsV1ResponseModelsItemsOneOf2Id:
      type: string
      enum:
        - gemini-2.5-flash
        - gemini-2.0-flash
        - gemini-2.0-flash-lite
      description: The unique identifier of the Google model
      title: AgentThinkModelsV1ResponseModelsItemsOneOf2Id
    AgentThinkModelsV1ResponseModelsItems2:
      type: object
      properties:
        id:
          $ref: '#/components/schemas/AgentThinkModelsV1ResponseModelsItemsOneOf2Id'
          description: The unique identifier of the Google model
        name:
          type: string
          description: The display name of the model
        provider:
          description: The provider of the model
      required:
        - id
        - name
        - provider
      description: Google models
      title: AgentThinkModelsV1ResponseModelsItems2
    AgentThinkModelsV1ResponseModelsItemsOneOf3Id:
      type: string
      enum:
        - openai/gpt-oss-20b
      description: The unique identifier of the Groq model
      title: AgentThinkModelsV1ResponseModelsItemsOneOf3Id
    AgentThinkModelsV1ResponseModelsItems3:
      type: object
      properties:
        id:
          $ref: '#/components/schemas/AgentThinkModelsV1ResponseModelsItemsOneOf3Id'
          description: The unique identifier of the Groq model
        name:
          type: string
          description: The display name of the model
        provider:
          description: The provider of the model
      required:
        - id
        - name
        - provider
      description: Groq models
      title: AgentThinkModelsV1ResponseModelsItems3
    AgentThinkModelsV1ResponseModelsItems4:
      type: object
      properties:
        id:
          type: string
          description: >-
            The unique identifier of the AWS Bedrock model (any model string
            accepted for BYO LLMs)
        name:
          type: string
          description: The display name of the model
        provider:
          description: The provider of the model
      required:
        - id
        - name
        - provider
      description: AWS Bedrock models (custom models accepted)
      title: AgentThinkModelsV1ResponseModelsItems4
    AgentThinkModelsV1ResponseModelsItems:
      oneOf:
        - $ref: '#/components/schemas/AgentThinkModelsV1ResponseModelsItems0'
        - $ref: '#/components/schemas/AgentThinkModelsV1ResponseModelsItems1'
        - $ref: '#/components/schemas/AgentThinkModelsV1ResponseModelsItems2'
        - $ref: '#/components/schemas/AgentThinkModelsV1ResponseModelsItems3'
        - $ref: '#/components/schemas/AgentThinkModelsV1ResponseModelsItems4'
      title: AgentThinkModelsV1ResponseModelsItems
    AgentThinkModelsV1Response:
      type: object
      properties:
        models:
          type: array
          items:
            $ref: '#/components/schemas/AgentThinkModelsV1ResponseModelsItems'
      required:
        - models
      title: AgentThinkModelsV1Response
    ErrorResponseTextError:
      type: string
      title: ErrorResponseTextError
    ErrorResponseLegacyError:
      type: object
      properties:
        err_code:
          type: string
          description: The error code
        err_msg:
          type: string
          description: The error message
        request_id:
          type: string
          description: The request ID
      title: ErrorResponseLegacyError
    ErrorResponseModernError:
      type: object
      properties:
        category:
          type: string
          description: The category of the error
        message:
          type: string
          description: A message about the error
        details:
          type: string
          description: A description of the error
        request_id:
          type: string
          description: The unique identifier of the request
      title: ErrorResponseModernError
    ErrorResponse:
      oneOf:
        - $ref: '#/components/schemas/ErrorResponseTextError'
        - $ref: '#/components/schemas/ErrorResponseLegacyError'
        - $ref: '#/components/schemas/ErrorResponseModernError'
      title: ErrorResponse

```

## SDK Code Examples

```python List supported models
import requests

url = "https://agent.deepgram.com/v1/agent/settings/think/models"
response = requests.get(url)

print(response.json())

```

```typescript List supported models
const res = await fetch(
  "https://agent.deepgram.com/v1/agent/settings/think/models",
);
const data = await res.json();
console.log(data);

```

```go
package main

import (
	"fmt"
	"net/http"
	"io"
)

func main() {

	url := "https://agent.deepgram.com/v1/agent/settings/think/models"

	req, _ := http.NewRequest("GET", url, nil)

	res, _ := http.DefaultClient.Do(req)

	defer res.Body.Close()
	body, _ := io.ReadAll(res.Body)

	fmt.Println(res)
	fmt.Println(string(body))

}
```

```ruby
require 'uri'
require 'net/http'

url = URI("https://agent.deepgram.com/v1/agent/settings/think/models")

http = Net::HTTP.new(url.host, url.port)
http.use_ssl = true

request = Net::HTTP::Get.new(url)

response = http.request(request)
puts response.read_body
```

```java
import com.mashape.unirest.http.HttpResponse;
import com.mashape.unirest.http.Unirest;

HttpResponse<String> response = Unirest.get("https://agent.deepgram.com/v1/agent/settings/think/models")
  .asString();
```

```php
<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('GET', 'https://agent.deepgram.com/v1/agent/settings/think/models');

echo $response->getBody();
```

```csharp
using RestSharp;

var client = new RestClient("https://agent.deepgram.com/v1/agent/settings/think/models");
var request = new RestRequest(Method.GET);
IRestResponse response = client.Execute(request);
```

```swift
import Foundation

let request = NSMutableURLRequest(url: NSURL(string: "https://agent.deepgram.com/v1/agent/settings/think/models")! as URL,
                                        cachePolicy: .useProtocolCachePolicy,
                                    timeoutInterval: 10.0)
request.httpMethod = "GET"

let session = URLSession.shared
let dataTask = session.dataTask(with: request as URLRequest, completionHandler: { (data, response, error) -> Void in
  if (error != nil) {
    print(error as Any)
  } else {
    let httpResponse = response as? HTTPURLResponse
    print(httpResponse)
  }
})

dataTask.resume()
```


# Create Agent Configuration

POST https://api.deepgram.com/v1/projects/{project_id}/agents
Content-Type: application/json

Creates a new reusable agent configuration. The `config` field must be a valid JSON string representing the `agent` block of a Settings message. The returned `agent_id` can be passed in place of the full `agent` object in future Settings messages.

Reference: https://developers.deepgram.com/reference/voice-agent/agent-configurations/create-agent-configuration

## OpenAPI Specification

```yaml
openapi: 3.1.0
info:
  title: Deepgram API Specification
  version: 1.0.0
paths:
  /v1/projects/{project_id}/agents:
    post:
      operationId: create
      summary: Create an Agent Configuration
      description: >-
        Creates a new reusable agent configuration. The `config` field must be a
        valid JSON string representing the `agent` block of a Settings message.
        The returned `agent_id` can be passed in place of the full `agent`
        object in future Settings messages.
      tags:
        - subpackage_voiceAgent.subpackage_voiceAgent/configurations
      parameters:
        - name: project_id
          in: path
          required: true
          schema:
            type: string
        - name: Authorization
          in: header
          description: |
            Use `Authorization: Token <API_KEY>`
            Example: `Authorization: Token 12345abcdef`
          required: true
          schema:
            type: string
      responses:
        '200':
          description: Agent configuration created successfully
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/CreateAgentConfigurationV1Response'
        '400':
          description: Invalid Request
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/ErrorResponse'
      requestBody:
        description: Agent configuration details
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/CreateAgentConfigurationV1Request'
servers:
  - url: https://api.deepgram.com
components:
  schemas:
    CreateAgentConfigurationV1Request:
      type: object
      properties:
        config:
          type: string
          description: >-
            A valid JSON string representing the agent block of a Settings
            message
        metadata:
          type: object
          additionalProperties:
            type: string
          description: >-
            A map of arbitrary key-value pairs for labeling or organizing the
            agent configuration
        api_version:
          type: integer
          default: 1
          description: API version. Defaults to 1
      required:
        - config
      description: Request body for creating an agent configuration
      title: CreateAgentConfigurationV1Request
    CreateAgentConfigurationV1ResponseConfig:
      type: object
      properties: {}
      description: The parsed agent configuration object
      title: CreateAgentConfigurationV1ResponseConfig
    CreateAgentConfigurationV1Response:
      type: object
      properties:
        agent_id:
          type: string
          description: The unique identifier of the newly created agent configuration
        config:
          $ref: '#/components/schemas/CreateAgentConfigurationV1ResponseConfig'
          description: The parsed agent configuration object
        metadata:
          type: object
          additionalProperties:
            type: string
          description: Metadata associated with the agent configuration
      required:
        - agent_id
        - config
      title: CreateAgentConfigurationV1Response
    ErrorResponseTextError:
      type: string
      title: ErrorResponseTextError
    ErrorResponseLegacyError:
      type: object
      properties:
        err_code:
          type: string
          description: The error code
        err_msg:
          type: string
          description: The error message
        request_id:
          type: string
          description: The request ID
      title: ErrorResponseLegacyError
    ErrorResponseModernError:
      type: object
      properties:
        category:
          type: string
          description: The category of the error
        message:
          type: string
          description: A message about the error
        details:
          type: string
          description: A description of the error
        request_id:
          type: string
          description: The unique identifier of the request
      title: ErrorResponseModernError
    ErrorResponse:
      oneOf:
        - $ref: '#/components/schemas/ErrorResponseTextError'
        - $ref: '#/components/schemas/ErrorResponseLegacyError'
        - $ref: '#/components/schemas/ErrorResponseModernError'
      title: ErrorResponse
  securitySchemes:
    ApiKeyAuth:
      type: apiKey
      in: header
      name: Authorization
      description: |
        Use `Authorization: Token <API_KEY>`
        Example: `Authorization: Token 12345abcdef`

```

## SDK Code Examples

```python
import requests

url = "https://api.deepgram.com/v1/projects/project_id/agents"

payload = { "config": "{\"language\":\"en-US\",\"model\":\"general\",\"punctuate\":true,\"profanity_filter\":false,\"diarize\":true}" }
headers = {
    "Authorization": "Token <apiKey>",
    "Content-Type": "application/json"
}

response = requests.post(url, json=payload, headers=headers)

print(response.json())
```

```javascript
const url = 'https://api.deepgram.com/v1/projects/project_id/agents';
const options = {
  method: 'POST',
  headers: {Authorization: 'Token <apiKey>', 'Content-Type': 'application/json'},
  body: '{"config":"{\"language\":\"en-US\",\"model\":\"general\",\"punctuate\":true,\"profanity_filter\":false,\"diarize\":true}"}'
};

try {
  const response = await fetch(url, options);
  const data = await response.json();
  console.log(data);
} catch (error) {
  console.error(error);
}
```

```go
package main

import (
	"fmt"
	"strings"
	"net/http"
	"io"
)

func main() {

	url := "https://api.deepgram.com/v1/projects/project_id/agents"

	payload := strings.NewReader("{\n  \"config\": \"{\\\"language\\\":\\\"en-US\\\",\\\"model\\\":\\\"general\\\",\\\"punctuate\\\":true,\\\"profanity_filter\\\":false,\\\"diarize\\\":true}\"\n}")

	req, _ := http.NewRequest("POST", url, payload)

	req.Header.Add("Authorization", "Token <apiKey>")
	req.Header.Add("Content-Type", "application/json")

	res, _ := http.DefaultClient.Do(req)

	defer res.Body.Close()
	body, _ := io.ReadAll(res.Body)

	fmt.Println(res)
	fmt.Println(string(body))

}
```

```ruby
require 'uri'
require 'net/http'

url = URI("https://api.deepgram.com/v1/projects/project_id/agents")

http = Net::HTTP.new(url.host, url.port)
http.use_ssl = true

request = Net::HTTP::Post.new(url)
request["Authorization"] = 'Token <apiKey>'
request["Content-Type"] = 'application/json'
request.body = "{\n  \"config\": \"{\\\"language\\\":\\\"en-US\\\",\\\"model\\\":\\\"general\\\",\\\"punctuate\\\":true,\\\"profanity_filter\\\":false,\\\"diarize\\\":true}\"\n}"

response = http.request(request)
puts response.read_body
```

```java
import com.mashape.unirest.http.HttpResponse;
import com.mashape.unirest.http.Unirest;

HttpResponse<String> response = Unirest.post("https://api.deepgram.com/v1/projects/project_id/agents")
  .header("Authorization", "Token <apiKey>")
  .header("Content-Type", "application/json")
  .body("{\n  \"config\": \"{\\\"language\\\":\\\"en-US\\\",\\\"model\\\":\\\"general\\\",\\\"punctuate\\\":true,\\\"profanity_filter\\\":false,\\\"diarize\\\":true}\"\n}")
  .asString();
```

```php
<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('POST', 'https://api.deepgram.com/v1/projects/project_id/agents', [
  'body' => '{
  "config": "{\\"language\\":\\"en-US\\",\\"model\\":\\"general\\",\\"punctuate\\":true,\\"profanity_filter\\":false,\\"diarize\\":true}"
}',
  'headers' => [
    'Authorization' => 'Token <apiKey>',
    'Content-Type' => 'application/json',
  ],
]);

echo $response->getBody();
```

```csharp
using RestSharp;

var client = new RestClient("https://api.deepgram.com/v1/projects/project_id/agents");
var request = new RestRequest(Method.POST);
request.AddHeader("Authorization", "Token <apiKey>");
request.AddHeader("Content-Type", "application/json");
request.AddParameter("application/json", "{\n  \"config\": \"{\\\"language\\\":\\\"en-US\\\",\\\"model\\\":\\\"general\\\",\\\"punctuate\\\":true,\\\"profanity_filter\\\":false,\\\"diarize\\\":true}\"\n}", ParameterType.RequestBody);
IRestResponse response = client.Execute(request);
```

```swift
import Foundation

let headers = [
  "Authorization": "Token <apiKey>",
  "Content-Type": "application/json"
]
let parameters = ["config": "{\"language\":\"en-US\",\"model\":\"general\",\"punctuate\":true,\"profanity_filter\":false,\"diarize\":true}"] as [String : Any]

let postData = JSONSerialization.data(withJSONObject: parameters, options: [])

let request = NSMutableURLRequest(url: NSURL(string: "https://api.deepgram.com/v1/projects/project_id/agents")! as URL,
                                        cachePolicy: .useProtocolCachePolicy,
                                    timeoutInterval: 10.0)
request.httpMethod = "POST"
request.allHTTPHeaderFields = headers
request.httpBody = postData as Data

let session = URLSession.shared
let dataTask = session.dataTask(with: request as URLRequest, completionHandler: { (data, response, error) -> Void in
  if (error != nil) {
    print(error as Any)
  } else {
    let httpResponse = response as? HTTPURLResponse
    print(httpResponse)
  }
})

dataTask.resume()
```

# List Agent Configurations

GET https://api.deepgram.com/v1/projects/{project_id}/agents

Returns all agent configurations for the specified project. Configurations are returned in their uninterpolated form—template variable placeholders appear as-is rather than with their substituted values.

Reference: https://developers.deepgram.com/reference/voice-agent/agent-configurations/list-agent-configurations

## OpenAPI Specification

```yaml
openapi: 3.1.0
info:
  title: Deepgram API Specification
  version: 1.0.0
paths:
  /v1/projects/{project_id}/agents:
    get:
      operationId: list
      summary: List Agent Configurations
      description: >-
        Returns all agent configurations for the specified project.
        Configurations are returned in their uninterpolated form—template
        variable placeholders appear as-is rather than with their substituted
        values.
      tags:
        - subpackage_voiceAgent.subpackage_voiceAgent/configurations
      parameters:
        - name: project_id
          in: path
          required: true
          schema:
            type: string
        - name: Authorization
          in: header
          description: |
            Use `Authorization: Token <API_KEY>`
            Example: `Authorization: Token 12345abcdef`
          required: true
          schema:
            type: string
      responses:
        '200':
          description: A list of agent configurations
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/ListAgentConfigurationsV1Response'
        '400':
          description: Invalid Request
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/ErrorResponse'
servers:
  - url: https://api.deepgram.com
components:
  schemas:
    AgentConfigurationV1Config:
      type: object
      properties: {}
      description: The agent configuration object
      title: AgentConfigurationV1Config
    AgentConfigurationV1:
      type: object
      properties:
        agent_id:
          type: string
          description: The unique identifier of the agent configuration
        config:
          $ref: '#/components/schemas/AgentConfigurationV1Config'
          description: The agent configuration object
        metadata:
          type: object
          additionalProperties:
            type: string
          description: >-
            A map of arbitrary key-value pairs for labeling or organizing the
            agent configuration
        created_at:
          type: string
          format: date-time
          description: Timestamp when the configuration was created
        updated_at:
          type: string
          format: date-time
          description: Timestamp when the configuration was last updated
      required:
        - agent_id
        - config
      description: A reusable agent configuration
      title: AgentConfigurationV1
    ListAgentConfigurationsV1Response:
      type: object
      properties:
        agents:
          type: array
          items:
            $ref: '#/components/schemas/AgentConfigurationV1'
          description: A list of agent configurations for the project
      title: ListAgentConfigurationsV1Response
    ErrorResponseTextError:
      type: string
      title: ErrorResponseTextError
    ErrorResponseLegacyError:
      type: object
      properties:
        err_code:
          type: string
          description: The error code
        err_msg:
          type: string
          description: The error message
        request_id:
          type: string
          description: The request ID
      title: ErrorResponseLegacyError
    ErrorResponseModernError:
      type: object
      properties:
        category:
          type: string
          description: The category of the error
        message:
          type: string
          description: A message about the error
        details:
          type: string
          description: A description of the error
        request_id:
          type: string
          description: The unique identifier of the request
      title: ErrorResponseModernError
    ErrorResponse:
      oneOf:
        - $ref: '#/components/schemas/ErrorResponseTextError'
        - $ref: '#/components/schemas/ErrorResponseLegacyError'
        - $ref: '#/components/schemas/ErrorResponseModernError'
      title: ErrorResponse
  securitySchemes:
    ApiKeyAuth:
      type: apiKey
      in: header
      name: Authorization
      description: |
        Use `Authorization: Token <API_KEY>`
        Example: `Authorization: Token 12345abcdef`

```

## SDK Code Examples

```python
import requests

url = "https://api.deepgram.com/v1/projects/project_id/agents"

payload = {}
headers = {
    "Authorization": "Token <apiKey>",
    "Content-Type": "application/json"
}

response = requests.get(url, json=payload, headers=headers)

print(response.json())
```

```javascript
const url = 'https://api.deepgram.com/v1/projects/project_id/agents';
const options = {
  method: 'GET',
  headers: {Authorization: 'Token <apiKey>', 'Content-Type': 'application/json'},
  body: '{}'
};

try {
  const response = await fetch(url, options);
  const data = await response.json();
  console.log(data);
} catch (error) {
  console.error(error);
}
```

```go
package main

import (
	"fmt"
	"strings"
	"net/http"
	"io"
)

func main() {

	url := "https://api.deepgram.com/v1/projects/project_id/agents"

	payload := strings.NewReader("{}")

	req, _ := http.NewRequest("GET", url, payload)

	req.Header.Add("Authorization", "Token <apiKey>")
	req.Header.Add("Content-Type", "application/json")

	res, _ := http.DefaultClient.Do(req)

	defer res.Body.Close()
	body, _ := io.ReadAll(res.Body)

	fmt.Println(res)
	fmt.Println(string(body))

}
```

```ruby
require 'uri'
require 'net/http'

url = URI("https://api.deepgram.com/v1/projects/project_id/agents")

http = Net::HTTP.new(url.host, url.port)
http.use_ssl = true

request = Net::HTTP::Get.new(url)
request["Authorization"] = 'Token <apiKey>'
request["Content-Type"] = 'application/json'
request.body = "{}"

response = http.request(request)
puts response.read_body
```

```java
import com.mashape.unirest.http.HttpResponse;
import com.mashape.unirest.http.Unirest;

HttpResponse<String> response = Unirest.get("https://api.deepgram.com/v1/projects/project_id/agents")
  .header("Authorization", "Token <apiKey>")
  .header("Content-Type", "application/json")
  .body("{}")
  .asString();
```

```php
<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('GET', 'https://api.deepgram.com/v1/projects/project_id/agents', [
  'body' => '{}',
  'headers' => [
    'Authorization' => 'Token <apiKey>',
    'Content-Type' => 'application/json',
  ],
]);

echo $response->getBody();
```

```csharp
using RestSharp;

var client = new RestClient("https://api.deepgram.com/v1/projects/project_id/agents");
var request = new RestRequest(Method.GET);
request.AddHeader("Authorization", "Token <apiKey>");
request.AddHeader("Content-Type", "application/json");
request.AddParameter("application/json", "{}", ParameterType.RequestBody);
IRestResponse response = client.Execute(request);
```

```swift
import Foundation

let headers = [
  "Authorization": "Token <apiKey>",
  "Content-Type": "application/json"
]
let parameters = [] as [String : Any]

let postData = JSONSerialization.data(withJSONObject: parameters, options: [])

let request = NSMutableURLRequest(url: NSURL(string: "https://api.deepgram.com/v1/projects/project_id/agents")! as URL,
                                        cachePolicy: .useProtocolCachePolicy,
                                    timeoutInterval: 10.0)
request.httpMethod = "GET"
request.allHTTPHeaderFields = headers
request.httpBody = postData as Data

let session = URLSession.shared
let dataTask = session.dataTask(with: request as URLRequest, completionHandler: { (data, response, error) -> Void in
  if (error != nil) {
    print(error as Any)
  } else {
    let httpResponse = response as? HTTPURLResponse
    print(httpResponse)
  }
})

dataTask.resume()
```


# Get Agent Configuration

GET https://api.deepgram.com/v1/projects/{project_id}/agents/{agent_id}

Returns the specified agent configuration in its uninterpolated form

Reference: https://developers.deepgram.com/reference/voice-agent/agent-configurations/get-agent-configuration

## OpenAPI Specification

```yaml
openapi: 3.1.0
info:
  title: Deepgram API Specification
  version: 1.0.0
paths:
  /v1/projects/{project_id}/agents/{agent_id}:
    get:
      operationId: get
      summary: Get an Agent Configuration
      description: Returns the specified agent configuration in its uninterpolated form
      tags:
        - subpackage_voiceAgent.subpackage_voiceAgent/configurations
      parameters:
        - name: project_id
          in: path
          required: true
          schema:
            type: string
        - name: agent_id
          in: path
          required: true
          schema:
            type: string
        - name: Authorization
          in: header
          description: |
            Use `Authorization: Token <API_KEY>`
            Example: `Authorization: Token 12345abcdef`
          required: true
          schema:
            type: string
      responses:
        '200':
          description: An agent configuration
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/AgentConfigurationV1'
        '400':
          description: Invalid Request
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/ErrorResponse'
servers:
  - url: https://api.deepgram.com
components:
  schemas:
    AgentConfigurationV1Config:
      type: object
      properties: {}
      description: The agent configuration object
      title: AgentConfigurationV1Config
    AgentConfigurationV1:
      type: object
      properties:
        agent_id:
          type: string
          description: The unique identifier of the agent configuration
        config:
          $ref: '#/components/schemas/AgentConfigurationV1Config'
          description: The agent configuration object
        metadata:
          type: object
          additionalProperties:
            type: string
          description: >-
            A map of arbitrary key-value pairs for labeling or organizing the
            agent configuration
        created_at:
          type: string
          format: date-time
          description: Timestamp when the configuration was created
        updated_at:
          type: string
          format: date-time
          description: Timestamp when the configuration was last updated
      required:
        - agent_id
        - config
      description: A reusable agent configuration
      title: AgentConfigurationV1
    ErrorResponseTextError:
      type: string
      title: ErrorResponseTextError
    ErrorResponseLegacyError:
      type: object
      properties:
        err_code:
          type: string
          description: The error code
        err_msg:
          type: string
          description: The error message
        request_id:
          type: string
          description: The request ID
      title: ErrorResponseLegacyError
    ErrorResponseModernError:
      type: object
      properties:
        category:
          type: string
          description: The category of the error
        message:
          type: string
          description: A message about the error
        details:
          type: string
          description: A description of the error
        request_id:
          type: string
          description: The unique identifier of the request
      title: ErrorResponseModernError
    ErrorResponse:
      oneOf:
        - $ref: '#/components/schemas/ErrorResponseTextError'
        - $ref: '#/components/schemas/ErrorResponseLegacyError'
        - $ref: '#/components/schemas/ErrorResponseModernError'
      title: ErrorResponse
  securitySchemes:
    ApiKeyAuth:
      type: apiKey
      in: header
      name: Authorization
      description: |
        Use `Authorization: Token <API_KEY>`
        Example: `Authorization: Token 12345abcdef`

```

## SDK Code Examples

```python
import requests

url = "https://api.deepgram.com/v1/projects/project_id/agents/agent_id"

payload = {}
headers = {
    "Authorization": "Token <apiKey>",
    "Content-Type": "application/json"
}

response = requests.get(url, json=payload, headers=headers)

print(response.json())
```

```javascript
const url = 'https://api.deepgram.com/v1/projects/project_id/agents/agent_id';
const options = {
  method: 'GET',
  headers: {Authorization: 'Token <apiKey>', 'Content-Type': 'application/json'},
  body: '{}'
};

try {
  const response = await fetch(url, options);
  const data = await response.json();
  console.log(data);
} catch (error) {
  console.error(error);
}
```

```go
package main

import (
	"fmt"
	"strings"
	"net/http"
	"io"
)

func main() {

	url := "https://api.deepgram.com/v1/projects/project_id/agents/agent_id"

	payload := strings.NewReader("{}")

	req, _ := http.NewRequest("GET", url, payload)

	req.Header.Add("Authorization", "Token <apiKey>")
	req.Header.Add("Content-Type", "application/json")

	res, _ := http.DefaultClient.Do(req)

	defer res.Body.Close()
	body, _ := io.ReadAll(res.Body)

	fmt.Println(res)
	fmt.Println(string(body))

}
```

```ruby
require 'uri'
require 'net/http'

url = URI("https://api.deepgram.com/v1/projects/project_id/agents/agent_id")

http = Net::HTTP.new(url.host, url.port)
http.use_ssl = true

request = Net::HTTP::Get.new(url)
request["Authorization"] = 'Token <apiKey>'
request["Content-Type"] = 'application/json'
request.body = "{}"

response = http.request(request)
puts response.read_body
```

```java
import com.mashape.unirest.http.HttpResponse;
import com.mashape.unirest.http.Unirest;

HttpResponse<String> response = Unirest.get("https://api.deepgram.com/v1/projects/project_id/agents/agent_id")
  .header("Authorization", "Token <apiKey>")
  .header("Content-Type", "application/json")
  .body("{}")
  .asString();
```

```php
<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('GET', 'https://api.deepgram.com/v1/projects/project_id/agents/agent_id', [
  'body' => '{}',
  'headers' => [
    'Authorization' => 'Token <apiKey>',
    'Content-Type' => 'application/json',
  ],
]);

echo $response->getBody();
```

```csharp
using RestSharp;

var client = new RestClient("https://api.deepgram.com/v1/projects/project_id/agents/agent_id");
var request = new RestRequest(Method.GET);
request.AddHeader("Authorization", "Token <apiKey>");
request.AddHeader("Content-Type", "application/json");
request.AddParameter("application/json", "{}", ParameterType.RequestBody);
IRestResponse response = client.Execute(request);
```

```swift
import Foundation

let headers = [
  "Authorization": "Token <apiKey>",
  "Content-Type": "application/json"
]
let parameters = [] as [String : Any]

let postData = JSONSerialization.data(withJSONObject: parameters, options: [])

let request = NSMutableURLRequest(url: NSURL(string: "https://api.deepgram.com/v1/projects/project_id/agents/agent_id")! as URL,
                                        cachePolicy: .useProtocolCachePolicy,
                                    timeoutInterval: 10.0)
request.httpMethod = "GET"
request.allHTTPHeaderFields = headers
request.httpBody = postData as Data

let session = URLSession.shared
let dataTask = session.dataTask(with: request as URLRequest, completionHandler: { (data, response, error) -> Void in
  if (error != nil) {
    print(error as Any)
  } else {
    let httpResponse = response as? HTTPURLResponse
    print(httpResponse)
  }
})

dataTask.resume()
```


# Update Agent Metadata

PUT https://api.deepgram.com/v1/projects/{project_id}/agents/{agent_id}
Content-Type: application/json

Updates the metadata associated with an agent configuration. The config itself is immutable—to change the configuration, delete the existing agent and create a new one.

Reference: https://developers.deepgram.com/reference/voice-agent/agent-configurations/update-agent-metadata

## OpenAPI Specification

```yaml
openapi: 3.1.0
info:
  title: Deepgram API Specification
  version: 1.0.0
paths:
  /v1/projects/{project_id}/agents/{agent_id}:
    put:
      operationId: update
      summary: Update Agent Metadata
      description: >-
        Updates the metadata associated with an agent configuration. The config
        itself is immutable—to change the configuration, delete the existing
        agent and create a new one.
      tags:
        - subpackage_voiceAgent.subpackage_voiceAgent/configurations
      parameters:
        - name: project_id
          in: path
          required: true
          schema:
            type: string
        - name: agent_id
          in: path
          required: true
          schema:
            type: string
        - name: Authorization
          in: header
          description: |
            Use `Authorization: Token <API_KEY>`
            Example: `Authorization: Token 12345abcdef`
          required: true
          schema:
            type: string
      responses:
        '200':
          description: Agent configuration updated
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/AgentConfigurationV1'
        '400':
          description: Invalid Request
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/ErrorResponse'
      requestBody:
        description: Updated metadata for the agent configuration
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/UpdateAgentMetadataV1Request'
servers:
  - url: https://api.deepgram.com
components:
  schemas:
    UpdateAgentMetadataV1Request:
      type: object
      properties:
        metadata:
          type: object
          additionalProperties:
            type: string
          description: >-
            A map of string key-value pairs to associate with this agent
            configuration
      required:
        - metadata
      description: Request body for updating agent configuration metadata
      title: UpdateAgentMetadataV1Request
    AgentConfigurationV1Config:
      type: object
      properties: {}
      description: The agent configuration object
      title: AgentConfigurationV1Config
    AgentConfigurationV1:
      type: object
      properties:
        agent_id:
          type: string
          description: The unique identifier of the agent configuration
        config:
          $ref: '#/components/schemas/AgentConfigurationV1Config'
          description: The agent configuration object
        metadata:
          type: object
          additionalProperties:
            type: string
          description: >-
            A map of arbitrary key-value pairs for labeling or organizing the
            agent configuration
        created_at:
          type: string
          format: date-time
          description: Timestamp when the configuration was created
        updated_at:
          type: string
          format: date-time
          description: Timestamp when the configuration was last updated
      required:
        - agent_id
        - config
      description: A reusable agent configuration
      title: AgentConfigurationV1
    ErrorResponseTextError:
      type: string
      title: ErrorResponseTextError
    ErrorResponseLegacyError:
      type: object
      properties:
        err_code:
          type: string
          description: The error code
        err_msg:
          type: string
          description: The error message
        request_id:
          type: string
          description: The request ID
      title: ErrorResponseLegacyError
    ErrorResponseModernError:
      type: object
      properties:
        category:
          type: string
          description: The category of the error
        message:
          type: string
          description: A message about the error
        details:
          type: string
          description: A description of the error
        request_id:
          type: string
          description: The unique identifier of the request
      title: ErrorResponseModernError
    ErrorResponse:
      oneOf:
        - $ref: '#/components/schemas/ErrorResponseTextError'
        - $ref: '#/components/schemas/ErrorResponseLegacyError'
        - $ref: '#/components/schemas/ErrorResponseModernError'
      title: ErrorResponse
  securitySchemes:
    ApiKeyAuth:
      type: apiKey
      in: header
      name: Authorization
      description: |
        Use `Authorization: Token <API_KEY>`
        Example: `Authorization: Token 12345abcdef`

```

## SDK Code Examples

```python
import requests

url = "https://api.deepgram.com/v1/projects/project_id/agents/agent_id"

payload = {}
headers = {
    "Authorization": "Token <apiKey>",
    "Content-Type": "application/json"
}

response = requests.put(url, json=payload, headers=headers)

print(response.json())
```

```javascript
const url = 'https://api.deepgram.com/v1/projects/project_id/agents/agent_id';
const options = {
  method: 'PUT',
  headers: {Authorization: 'Token <apiKey>', 'Content-Type': 'application/json'},
  body: '{}'
};

try {
  const response = await fetch(url, options);
  const data = await response.json();
  console.log(data);
} catch (error) {
  console.error(error);
}
```

```go
package main

import (
	"fmt"
	"strings"
	"net/http"
	"io"
)

func main() {

	url := "https://api.deepgram.com/v1/projects/project_id/agents/agent_id"

	payload := strings.NewReader("{}")

	req, _ := http.NewRequest("PUT", url, payload)

	req.Header.Add("Authorization", "Token <apiKey>")
	req.Header.Add("Content-Type", "application/json")

	res, _ := http.DefaultClient.Do(req)

	defer res.Body.Close()
	body, _ := io.ReadAll(res.Body)

	fmt.Println(res)
	fmt.Println(string(body))

}
```

```ruby
require 'uri'
require 'net/http'

url = URI("https://api.deepgram.com/v1/projects/project_id/agents/agent_id")

http = Net::HTTP.new(url.host, url.port)
http.use_ssl = true

request = Net::HTTP::Put.new(url)
request["Authorization"] = 'Token <apiKey>'
request["Content-Type"] = 'application/json'
request.body = "{}"

response = http.request(request)
puts response.read_body
```

```java
import com.mashape.unirest.http.HttpResponse;
import com.mashape.unirest.http.Unirest;

HttpResponse<String> response = Unirest.put("https://api.deepgram.com/v1/projects/project_id/agents/agent_id")
  .header("Authorization", "Token <apiKey>")
  .header("Content-Type", "application/json")
  .body("{}")
  .asString();
```

```php
<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('PUT', 'https://api.deepgram.com/v1/projects/project_id/agents/agent_id', [
  'body' => '{}',
  'headers' => [
    'Authorization' => 'Token <apiKey>',
    'Content-Type' => 'application/json',
  ],
]);

echo $response->getBody();
```

```csharp
using RestSharp;

var client = new RestClient("https://api.deepgram.com/v1/projects/project_id/agents/agent_id");
var request = new RestRequest(Method.PUT);
request.AddHeader("Authorization", "Token <apiKey>");
request.AddHeader("Content-Type", "application/json");
request.AddParameter("application/json", "{}", ParameterType.RequestBody);
IRestResponse response = client.Execute(request);
```

```swift
import Foundation

let headers = [
  "Authorization": "Token <apiKey>",
  "Content-Type": "application/json"
]
let parameters = [] as [String : Any]

let postData = JSONSerialization.data(withJSONObject: parameters, options: [])

let request = NSMutableURLRequest(url: NSURL(string: "https://api.deepgram.com/v1/projects/project_id/agents/agent_id")! as URL,
                                        cachePolicy: .useProtocolCachePolicy,
                                    timeoutInterval: 10.0)
request.httpMethod = "PUT"
request.allHTTPHeaderFields = headers
request.httpBody = postData as Data

let session = URLSession.shared
let dataTask = session.dataTask(with: request as URLRequest, completionHandler: { (data, response, error) -> Void in
  if (error != nil) {
    print(error as Any)
  } else {
    let httpResponse = response as? HTTPURLResponse
    print(httpResponse)
  }
})

dataTask.resume()
```


# Delete Agent Configuration

DELETE https://api.deepgram.com/v1/projects/{project_id}/agents/{agent_id}

Deletes the specified agent configuration. Deleting an agent configuration can cause a production outage if your service references this agent UUID. Migrate all active sessions to a new configuration before deleting.

Reference: https://developers.deepgram.com/reference/voice-agent/agent-configurations/delete-agent-configuration

## OpenAPI Specification

```yaml
openapi: 3.1.0
info:
  title: Deepgram API Specification
  version: 1.0.0
paths:
  /v1/projects/{project_id}/agents/{agent_id}:
    delete:
      operationId: delete
      summary: Delete an Agent Configuration
      description: >-
        Deletes the specified agent configuration. Deleting an agent
        configuration can cause a production outage if your service references
        this agent UUID. Migrate all active sessions to a new configuration
        before deleting.
      tags:
        - subpackage_voiceAgent.subpackage_voiceAgent/configurations
      parameters:
        - name: project_id
          in: path
          required: true
          schema:
            type: string
        - name: agent_id
          in: path
          required: true
          schema:
            type: string
        - name: Authorization
          in: header
          description: |
            Use `Authorization: Token <API_KEY>`
            Example: `Authorization: Token 12345abcdef`
          required: true
          schema:
            type: string
      responses:
        '200':
          description: Agent configuration deleted
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/DeleteAgentConfigurationV1Response'
        '400':
          description: Invalid Request
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/ErrorResponse'
servers:
  - url: https://api.deepgram.com
components:
  schemas:
    DeleteAgentConfigurationV1Response:
      type: object
      properties: {}
      description: Confirmation that the agent configuration was deleted
      title: DeleteAgentConfigurationV1Response
    ErrorResponseTextError:
      type: string
      title: ErrorResponseTextError
    ErrorResponseLegacyError:
      type: object
      properties:
        err_code:
          type: string
          description: The error code
        err_msg:
          type: string
          description: The error message
        request_id:
          type: string
          description: The request ID
      title: ErrorResponseLegacyError
    ErrorResponseModernError:
      type: object
      properties:
        category:
          type: string
          description: The category of the error
        message:
          type: string
          description: A message about the error
        details:
          type: string
          description: A description of the error
        request_id:
          type: string
          description: The unique identifier of the request
      title: ErrorResponseModernError
    ErrorResponse:
      oneOf:
        - $ref: '#/components/schemas/ErrorResponseTextError'
        - $ref: '#/components/schemas/ErrorResponseLegacyError'
        - $ref: '#/components/schemas/ErrorResponseModernError'
      title: ErrorResponse
  securitySchemes:
    ApiKeyAuth:
      type: apiKey
      in: header
      name: Authorization
      description: |
        Use `Authorization: Token <API_KEY>`
        Example: `Authorization: Token 12345abcdef`

```

## SDK Code Examples

```python
import requests

url = "https://api.deepgram.com/v1/projects/project_id/agents/agent_id"

payload = {}
headers = {
    "Authorization": "Token <apiKey>",
    "Content-Type": "application/json"
}

response = requests.delete(url, json=payload, headers=headers)

print(response.json())
```

```javascript
const url = 'https://api.deepgram.com/v1/projects/project_id/agents/agent_id';
const options = {
  method: 'DELETE',
  headers: {Authorization: 'Token <apiKey>', 'Content-Type': 'application/json'},
  body: '{}'
};

try {
  const response = await fetch(url, options);
  const data = await response.json();
  console.log(data);
} catch (error) {
  console.error(error);
}
```

```go
package main

import (
	"fmt"
	"strings"
	"net/http"
	"io"
)

func main() {

	url := "https://api.deepgram.com/v1/projects/project_id/agents/agent_id"

	payload := strings.NewReader("{}")

	req, _ := http.NewRequest("DELETE", url, payload)

	req.Header.Add("Authorization", "Token <apiKey>")
	req.Header.Add("Content-Type", "application/json")

	res, _ := http.DefaultClient.Do(req)

	defer res.Body.Close()
	body, _ := io.ReadAll(res.Body)

	fmt.Println(res)
	fmt.Println(string(body))

}
```

```ruby
require 'uri'
require 'net/http'

url = URI("https://api.deepgram.com/v1/projects/project_id/agents/agent_id")

http = Net::HTTP.new(url.host, url.port)
http.use_ssl = true

request = Net::HTTP::Delete.new(url)
request["Authorization"] = 'Token <apiKey>'
request["Content-Type"] = 'application/json'
request.body = "{}"

response = http.request(request)
puts response.read_body
```

```java
import com.mashape.unirest.http.HttpResponse;
import com.mashape.unirest.http.Unirest;

HttpResponse<String> response = Unirest.delete("https://api.deepgram.com/v1/projects/project_id/agents/agent_id")
  .header("Authorization", "Token <apiKey>")
  .header("Content-Type", "application/json")
  .body("{}")
  .asString();
```

```php
<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('DELETE', 'https://api.deepgram.com/v1/projects/project_id/agents/agent_id', [
  'body' => '{}',
  'headers' => [
    'Authorization' => 'Token <apiKey>',
    'Content-Type' => 'application/json',
  ],
]);

echo $response->getBody();
```

```csharp
using RestSharp;

var client = new RestClient("https://api.deepgram.com/v1/projects/project_id/agents/agent_id");
var request = new RestRequest(Method.DELETE);
request.AddHeader("Authorization", "Token <apiKey>");
request.AddHeader("Content-Type", "application/json");
request.AddParameter("application/json", "{}", ParameterType.RequestBody);
IRestResponse response = client.Execute(request);
```

```swift
import Foundation

let headers = [
  "Authorization": "Token <apiKey>",
  "Content-Type": "application/json"
]
let parameters = [] as [String : Any]

let postData = JSONSerialization.data(withJSONObject: parameters, options: [])

let request = NSMutableURLRequest(url: NSURL(string: "https://api.deepgram.com/v1/projects/project_id/agents/agent_id")! as URL,
                                        cachePolicy: .useProtocolCachePolicy,
                                    timeoutInterval: 10.0)
request.httpMethod = "DELETE"
request.allHTTPHeaderFields = headers
request.httpBody = postData as Data

let session = URLSession.shared
let dataTask = session.dataTask(with: request as URLRequest, completionHandler: { (data, response, error) -> Void in
  if (error != nil) {
    print(error as Any)
  } else {
    let httpResponse = response as? HTTPURLResponse
    print(httpResponse)
  }
})

dataTask.resume()
```


# Create Agent Variable

POST https://api.deepgram.com/v1/projects/{project_id}/agent-variables
Content-Type: application/json

Creates a new template variable. Variables follow the `DG_<VARIABLE_NAME>` naming format and can substitute any JSON value in an agent configuration.

Reference: https://developers.deepgram.com/reference/voice-agent/agent-variables/create-agent-variable

## OpenAPI Specification

```yaml
openapi: 3.1.0
info:
  title: Deepgram API Specification
  version: 1.0.0
paths:
  /v1/projects/{project_id}/agent-variables:
    post:
      operationId: create
      summary: Create an Agent Variable
      description: >-
        Creates a new template variable. Variables follow the
        `DG_<VARIABLE_NAME>` naming format and can substitute any JSON value in
        an agent configuration.
      tags:
        - subpackage_voiceAgent.subpackage_voiceAgent/variables
      parameters:
        - name: project_id
          in: path
          required: true
          schema:
            type: string
        - name: Authorization
          in: header
          description: |
            Use `Authorization: Token <API_KEY>`
            Example: `Authorization: Token 12345abcdef`
          required: true
          schema:
            type: string
      responses:
        '200':
          description: Agent variable created successfully
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/AgentVariableV1'
        '400':
          description: Invalid Request
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/ErrorResponse'
      requestBody:
        description: Agent variable details
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/CreateAgentVariableV1Request'
servers:
  - url: https://api.deepgram.com
components:
  schemas:
    CreateAgentVariableV1Request:
      type: object
      properties:
        key:
          type: string
          description: The variable name, following the DG_<VARIABLE_NAME> format
        value:
          description: >-
            The value to substitute. Can be any valid JSON type (string, number,
            boolean, object, or array)
        api_version:
          type: integer
          default: 1
          description: API version. Defaults to 1
      required:
        - key
        - value
      description: Request body for creating an agent variable
      title: CreateAgentVariableV1Request
    AgentVariableV1:
      type: object
      properties:
        variable_id:
          type: string
          description: The unique identifier of the variable
        key:
          type: string
          description: The variable name, following the DG_<VARIABLE_NAME> format
        value:
          description: The value to substitute. Can be any valid JSON type
        created_at:
          type: string
          format: date-time
          description: Timestamp when the variable was created
        updated_at:
          type: string
          format: date-time
          description: Timestamp when the variable was last updated
      required:
        - variable_id
        - key
        - value
      description: A template variable for agent configurations
      title: AgentVariableV1
    ErrorResponseTextError:
      type: string
      title: ErrorResponseTextError
    ErrorResponseLegacyError:
      type: object
      properties:
        err_code:
          type: string
          description: The error code
        err_msg:
          type: string
          description: The error message
        request_id:
          type: string
          description: The request ID
      title: ErrorResponseLegacyError
    ErrorResponseModernError:
      type: object
      properties:
        category:
          type: string
          description: The category of the error
        message:
          type: string
          description: A message about the error
        details:
          type: string
          description: A description of the error
        request_id:
          type: string
          description: The unique identifier of the request
      title: ErrorResponseModernError
    ErrorResponse:
      oneOf:
        - $ref: '#/components/schemas/ErrorResponseTextError'
        - $ref: '#/components/schemas/ErrorResponseLegacyError'
        - $ref: '#/components/schemas/ErrorResponseModernError'
      title: ErrorResponse
  securitySchemes:
    ApiKeyAuth:
      type: apiKey
      in: header
      name: Authorization
      description: |
        Use `Authorization: Token <API_KEY>`
        Example: `Authorization: Token 12345abcdef`

```

## SDK Code Examples

```python
import requests

url = "https://api.deepgram.com/v1/projects/project_id/agent-variables"

payload = {
    "key": "DG_API_TIMEOUT",
    "value": 30
}
headers = {
    "Authorization": "Token <apiKey>",
    "Content-Type": "application/json"
}

response = requests.post(url, json=payload, headers=headers)

print(response.json())
```

```javascript
const url = 'https://api.deepgram.com/v1/projects/project_id/agent-variables';
const options = {
  method: 'POST',
  headers: {Authorization: 'Token <apiKey>', 'Content-Type': 'application/json'},
  body: '{"key":"DG_API_TIMEOUT","value":30}'
};

try {
  const response = await fetch(url, options);
  const data = await response.json();
  console.log(data);
} catch (error) {
  console.error(error);
}
```

```go
package main

import (
	"fmt"
	"strings"
	"net/http"
	"io"
)

func main() {

	url := "https://api.deepgram.com/v1/projects/project_id/agent-variables"

	payload := strings.NewReader("{\n  \"key\": \"DG_API_TIMEOUT\",\n  \"value\": 30\n}")

	req, _ := http.NewRequest("POST", url, payload)

	req.Header.Add("Authorization", "Token <apiKey>")
	req.Header.Add("Content-Type", "application/json")

	res, _ := http.DefaultClient.Do(req)

	defer res.Body.Close()
	body, _ := io.ReadAll(res.Body)

	fmt.Println(res)
	fmt.Println(string(body))

}
```

```ruby
require 'uri'
require 'net/http'

url = URI("https://api.deepgram.com/v1/projects/project_id/agent-variables")

http = Net::HTTP.new(url.host, url.port)
http.use_ssl = true

request = Net::HTTP::Post.new(url)
request["Authorization"] = 'Token <apiKey>'
request["Content-Type"] = 'application/json'
request.body = "{\n  \"key\": \"DG_API_TIMEOUT\",\n  \"value\": 30\n}"

response = http.request(request)
puts response.read_body
```

```java
import com.mashape.unirest.http.HttpResponse;
import com.mashape.unirest.http.Unirest;

HttpResponse<String> response = Unirest.post("https://api.deepgram.com/v1/projects/project_id/agent-variables")
  .header("Authorization", "Token <apiKey>")
  .header("Content-Type", "application/json")
  .body("{\n  \"key\": \"DG_API_TIMEOUT\",\n  \"value\": 30\n}")
  .asString();
```

```php
<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('POST', 'https://api.deepgram.com/v1/projects/project_id/agent-variables', [
  'body' => '{
  "key": "DG_API_TIMEOUT",
  "value": 30
}',
  'headers' => [
    'Authorization' => 'Token <apiKey>',
    'Content-Type' => 'application/json',
  ],
]);

echo $response->getBody();
```

```csharp
using RestSharp;

var client = new RestClient("https://api.deepgram.com/v1/projects/project_id/agent-variables");
var request = new RestRequest(Method.POST);
request.AddHeader("Authorization", "Token <apiKey>");
request.AddHeader("Content-Type", "application/json");
request.AddParameter("application/json", "{\n  \"key\": \"DG_API_TIMEOUT\",\n  \"value\": 30\n}", ParameterType.RequestBody);
IRestResponse response = client.Execute(request);
```

```swift
import Foundation

let headers = [
  "Authorization": "Token <apiKey>",
  "Content-Type": "application/json"
]
let parameters = [
  "key": "DG_API_TIMEOUT",
  "value": 30
] as [String : Any]

let postData = JSONSerialization.data(withJSONObject: parameters, options: [])

let request = NSMutableURLRequest(url: NSURL(string: "https://api.deepgram.com/v1/projects/project_id/agent-variables")! as URL,
                                        cachePolicy: .useProtocolCachePolicy,
                                    timeoutInterval: 10.0)
request.httpMethod = "POST"
request.allHTTPHeaderFields = headers
request.httpBody = postData as Data

let session = URLSession.shared
let dataTask = session.dataTask(with: request as URLRequest, completionHandler: { (data, response, error) -> Void in
  if (error != nil) {
    print(error as Any)
  } else {
    let httpResponse = response as? HTTPURLResponse
    print(httpResponse)
  }
})

dataTask.resume()
```


# List Agent Variables

GET https://api.deepgram.com/v1/projects/{project_id}/agent-variables

Returns all template variables for the specified project

Reference: https://developers.deepgram.com/reference/voice-agent/agent-variables/list-agent-variables

## OpenAPI Specification

```yaml
openapi: 3.1.0
info:
  title: Deepgram API Specification
  version: 1.0.0
paths:
  /v1/projects/{project_id}/agent-variables:
    get:
      operationId: list
      summary: List Agent Variables
      description: Returns all template variables for the specified project
      tags:
        - subpackage_voiceAgent.subpackage_voiceAgent/variables
      parameters:
        - name: project_id
          in: path
          required: true
          schema:
            type: string
        - name: Authorization
          in: header
          description: |
            Use `Authorization: Token <API_KEY>`
            Example: `Authorization: Token 12345abcdef`
          required: true
          schema:
            type: string
      responses:
        '200':
          description: A list of agent variables
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/ListAgentVariablesV1Response'
        '400':
          description: Invalid Request
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/ErrorResponse'
servers:
  - url: https://api.deepgram.com
components:
  schemas:
    AgentVariableV1:
      type: object
      properties:
        variable_id:
          type: string
          description: The unique identifier of the variable
        key:
          type: string
          description: The variable name, following the DG_<VARIABLE_NAME> format
        value:
          description: The value to substitute. Can be any valid JSON type
        created_at:
          type: string
          format: date-time
          description: Timestamp when the variable was created
        updated_at:
          type: string
          format: date-time
          description: Timestamp when the variable was last updated
      required:
        - variable_id
        - key
        - value
      description: A template variable for agent configurations
      title: AgentVariableV1
    ListAgentVariablesV1Response:
      type: object
      properties:
        variables:
          type: array
          items:
            $ref: '#/components/schemas/AgentVariableV1'
          description: A list of agent variables for the project
      title: ListAgentVariablesV1Response
    ErrorResponseTextError:
      type: string
      title: ErrorResponseTextError
    ErrorResponseLegacyError:
      type: object
      properties:
        err_code:
          type: string
          description: The error code
        err_msg:
          type: string
          description: The error message
        request_id:
          type: string
          description: The request ID
      title: ErrorResponseLegacyError
    ErrorResponseModernError:
      type: object
      properties:
        category:
          type: string
          description: The category of the error
        message:
          type: string
          description: A message about the error
        details:
          type: string
          description: A description of the error
        request_id:
          type: string
          description: The unique identifier of the request
      title: ErrorResponseModernError
    ErrorResponse:
      oneOf:
        - $ref: '#/components/schemas/ErrorResponseTextError'
        - $ref: '#/components/schemas/ErrorResponseLegacyError'
        - $ref: '#/components/schemas/ErrorResponseModernError'
      title: ErrorResponse
  securitySchemes:
    ApiKeyAuth:
      type: apiKey
      in: header
      name: Authorization
      description: |
        Use `Authorization: Token <API_KEY>`
        Example: `Authorization: Token 12345abcdef`

```

## SDK Code Examples

```python
import requests

url = "https://api.deepgram.com/v1/projects/project_id/agent-variables"

payload = {}
headers = {
    "Authorization": "Token <apiKey>",
    "Content-Type": "application/json"
}

response = requests.get(url, json=payload, headers=headers)

print(response.json())
```

```javascript
const url = 'https://api.deepgram.com/v1/projects/project_id/agent-variables';
const options = {
  method: 'GET',
  headers: {Authorization: 'Token <apiKey>', 'Content-Type': 'application/json'},
  body: '{}'
};

try {
  const response = await fetch(url, options);
  const data = await response.json();
  console.log(data);
} catch (error) {
  console.error(error);
}
```

```go
package main

import (
	"fmt"
	"strings"
	"net/http"
	"io"
)

func main() {

	url := "https://api.deepgram.com/v1/projects/project_id/agent-variables"

	payload := strings.NewReader("{}")

	req, _ := http.NewRequest("GET", url, payload)

	req.Header.Add("Authorization", "Token <apiKey>")
	req.Header.Add("Content-Type", "application/json")

	res, _ := http.DefaultClient.Do(req)

	defer res.Body.Close()
	body, _ := io.ReadAll(res.Body)

	fmt.Println(res)
	fmt.Println(string(body))

}
```

```ruby
require 'uri'
require 'net/http'

url = URI("https://api.deepgram.com/v1/projects/project_id/agent-variables")

http = Net::HTTP.new(url.host, url.port)
http.use_ssl = true

request = Net::HTTP::Get.new(url)
request["Authorization"] = 'Token <apiKey>'
request["Content-Type"] = 'application/json'
request.body = "{}"

response = http.request(request)
puts response.read_body
```

```java
import com.mashape.unirest.http.HttpResponse;
import com.mashape.unirest.http.Unirest;

HttpResponse<String> response = Unirest.get("https://api.deepgram.com/v1/projects/project_id/agent-variables")
  .header("Authorization", "Token <apiKey>")
  .header("Content-Type", "application/json")
  .body("{}")
  .asString();
```

```php
<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('GET', 'https://api.deepgram.com/v1/projects/project_id/agent-variables', [
  'body' => '{}',
  'headers' => [
    'Authorization' => 'Token <apiKey>',
    'Content-Type' => 'application/json',
  ],
]);

echo $response->getBody();
```

```csharp
using RestSharp;

var client = new RestClient("https://api.deepgram.com/v1/projects/project_id/agent-variables");
var request = new RestRequest(Method.GET);
request.AddHeader("Authorization", "Token <apiKey>");
request.AddHeader("Content-Type", "application/json");
request.AddParameter("application/json", "{}", ParameterType.RequestBody);
IRestResponse response = client.Execute(request);
```

```swift
import Foundation

let headers = [
  "Authorization": "Token <apiKey>",
  "Content-Type": "application/json"
]
let parameters = [] as [String : Any]

let postData = JSONSerialization.data(withJSONObject: parameters, options: [])

let request = NSMutableURLRequest(url: NSURL(string: "https://api.deepgram.com/v1/projects/project_id/agent-variables")! as URL,
                                        cachePolicy: .useProtocolCachePolicy,
                                    timeoutInterval: 10.0)
request.httpMethod = "GET"
request.allHTTPHeaderFields = headers
request.httpBody = postData as Data

let session = URLSession.shared
let dataTask = session.dataTask(with: request as URLRequest, completionHandler: { (data, response, error) -> Void in
  if (error != nil) {
    print(error as Any)
  } else {
    let httpResponse = response as? HTTPURLResponse
    print(httpResponse)
  }
})

dataTask.resume()
```


# Get Agent Variable

GET https://api.deepgram.com/v1/projects/{project_id}/agent-variables/{variable_id}

Returns the specified template variable

Reference: https://developers.deepgram.com/reference/voice-agent/agent-variables/get-agent-variable

## OpenAPI Specification

```yaml
openapi: 3.1.0
info:
  title: Deepgram API Specification
  version: 1.0.0
paths:
  /v1/projects/{project_id}/agent-variables/{variable_id}:
    get:
      operationId: get
      summary: Get an Agent Variable
      description: Returns the specified template variable
      tags:
        - subpackage_voiceAgent.subpackage_voiceAgent/variables
      parameters:
        - name: project_id
          in: path
          required: true
          schema:
            type: string
        - name: variable_id
          in: path
          required: true
          schema:
            type: string
        - name: Authorization
          in: header
          description: |
            Use `Authorization: Token <API_KEY>`
            Example: `Authorization: Token 12345abcdef`
          required: true
          schema:
            type: string
      responses:
        '200':
          description: An agent variable
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/AgentVariableV1'
        '400':
          description: Invalid Request
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/ErrorResponse'
servers:
  - url: https://api.deepgram.com
components:
  schemas:
    AgentVariableV1:
      type: object
      properties:
        variable_id:
          type: string
          description: The unique identifier of the variable
        key:
          type: string
          description: The variable name, following the DG_<VARIABLE_NAME> format
        value:
          description: The value to substitute. Can be any valid JSON type
        created_at:
          type: string
          format: date-time
          description: Timestamp when the variable was created
        updated_at:
          type: string
          format: date-time
          description: Timestamp when the variable was last updated
      required:
        - variable_id
        - key
        - value
      description: A template variable for agent configurations
      title: AgentVariableV1
    ErrorResponseTextError:
      type: string
      title: ErrorResponseTextError
    ErrorResponseLegacyError:
      type: object
      properties:
        err_code:
          type: string
          description: The error code
        err_msg:
          type: string
          description: The error message
        request_id:
          type: string
          description: The request ID
      title: ErrorResponseLegacyError
    ErrorResponseModernError:
      type: object
      properties:
        category:
          type: string
          description: The category of the error
        message:
          type: string
          description: A message about the error
        details:
          type: string
          description: A description of the error
        request_id:
          type: string
          description: The unique identifier of the request
      title: ErrorResponseModernError
    ErrorResponse:
      oneOf:
        - $ref: '#/components/schemas/ErrorResponseTextError'
        - $ref: '#/components/schemas/ErrorResponseLegacyError'
        - $ref: '#/components/schemas/ErrorResponseModernError'
      title: ErrorResponse
  securitySchemes:
    ApiKeyAuth:
      type: apiKey
      in: header
      name: Authorization
      description: |
        Use `Authorization: Token <API_KEY>`
        Example: `Authorization: Token 12345abcdef`

```

## SDK Code Examples

```python
import requests

url = "https://api.deepgram.com/v1/projects/project_id/agent-variables/variable_id"

payload = {}
headers = {
    "Authorization": "Token <apiKey>",
    "Content-Type": "application/json"
}

response = requests.get(url, json=payload, headers=headers)

print(response.json())
```

```javascript
const url = 'https://api.deepgram.com/v1/projects/project_id/agent-variables/variable_id';
const options = {
  method: 'GET',
  headers: {Authorization: 'Token <apiKey>', 'Content-Type': 'application/json'},
  body: '{}'
};

try {
  const response = await fetch(url, options);
  const data = await response.json();
  console.log(data);
} catch (error) {
  console.error(error);
}
```

```go
package main

import (
	"fmt"
	"strings"
	"net/http"
	"io"
)

func main() {

	url := "https://api.deepgram.com/v1/projects/project_id/agent-variables/variable_id"

	payload := strings.NewReader("{}")

	req, _ := http.NewRequest("GET", url, payload)

	req.Header.Add("Authorization", "Token <apiKey>")
	req.Header.Add("Content-Type", "application/json")

	res, _ := http.DefaultClient.Do(req)

	defer res.Body.Close()
	body, _ := io.ReadAll(res.Body)

	fmt.Println(res)
	fmt.Println(string(body))

}
```

```ruby
require 'uri'
require 'net/http'

url = URI("https://api.deepgram.com/v1/projects/project_id/agent-variables/variable_id")

http = Net::HTTP.new(url.host, url.port)
http.use_ssl = true

request = Net::HTTP::Get.new(url)
request["Authorization"] = 'Token <apiKey>'
request["Content-Type"] = 'application/json'
request.body = "{}"

response = http.request(request)
puts response.read_body
```

```java
import com.mashape.unirest.http.HttpResponse;
import com.mashape.unirest.http.Unirest;

HttpResponse<String> response = Unirest.get("https://api.deepgram.com/v1/projects/project_id/agent-variables/variable_id")
  .header("Authorization", "Token <apiKey>")
  .header("Content-Type", "application/json")
  .body("{}")
  .asString();
```

```php
<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('GET', 'https://api.deepgram.com/v1/projects/project_id/agent-variables/variable_id', [
  'body' => '{}',
  'headers' => [
    'Authorization' => 'Token <apiKey>',
    'Content-Type' => 'application/json',
  ],
]);

echo $response->getBody();
```

```csharp
using RestSharp;

var client = new RestClient("https://api.deepgram.com/v1/projects/project_id/agent-variables/variable_id");
var request = new RestRequest(Method.GET);
request.AddHeader("Authorization", "Token <apiKey>");
request.AddHeader("Content-Type", "application/json");
request.AddParameter("application/json", "{}", ParameterType.RequestBody);
IRestResponse response = client.Execute(request);
```

```swift
import Foundation

let headers = [
  "Authorization": "Token <apiKey>",
  "Content-Type": "application/json"
]
let parameters = [] as [String : Any]

let postData = JSONSerialization.data(withJSONObject: parameters, options: [])

let request = NSMutableURLRequest(url: NSURL(string: "https://api.deepgram.com/v1/projects/project_id/agent-variables/variable_id")! as URL,
                                        cachePolicy: .useProtocolCachePolicy,
                                    timeoutInterval: 10.0)
request.httpMethod = "GET"
request.allHTTPHeaderFields = headers
request.httpBody = postData as Data

let session = URLSession.shared
let dataTask = session.dataTask(with: request as URLRequest, completionHandler: { (data, response, error) -> Void in
  if (error != nil) {
    print(error as Any)
  } else {
    let httpResponse = response as? HTTPURLResponse
    print(httpResponse)
  }
})

dataTask.resume()
```

# Update Agent Variable

PATCH https://api.deepgram.com/v1/projects/{project_id}/agent-variables/{variable_id}
Content-Type: application/json

Updates the value of an existing template variable

Reference: https://developers.deepgram.com/reference/voice-agent/agent-variables/update-agent-variable

## OpenAPI Specification

```yaml
openapi: 3.1.0
info:
  title: Deepgram API Specification
  version: 1.0.0
paths:
  /v1/projects/{project_id}/agent-variables/{variable_id}:
    patch:
      operationId: update
      summary: Update an Agent Variable
      description: Updates the value of an existing template variable
      tags:
        - subpackage_voiceAgent.subpackage_voiceAgent/variables
      parameters:
        - name: project_id
          in: path
          required: true
          schema:
            type: string
        - name: variable_id
          in: path
          required: true
          schema:
            type: string
        - name: Authorization
          in: header
          description: |
            Use `Authorization: Token <API_KEY>`
            Example: `Authorization: Token 12345abcdef`
          required: true
          schema:
            type: string
      responses:
        '200':
          description: Agent variable updated
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/AgentVariableV1'
        '400':
          description: Invalid Request
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/ErrorResponse'
      requestBody:
        description: Updated value for the agent variable
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/UpdateAgentVariableV1Request'
servers:
  - url: https://api.deepgram.com
components:
  schemas:
    UpdateAgentVariableV1Request:
      type: object
      properties:
        value:
          description: The new value to substitute
      required:
        - value
      description: Request body for updating an agent variable
      title: UpdateAgentVariableV1Request
    AgentVariableV1:
      type: object
      properties:
        variable_id:
          type: string
          description: The unique identifier of the variable
        key:
          type: string
          description: The variable name, following the DG_<VARIABLE_NAME> format
        value:
          description: The value to substitute. Can be any valid JSON type
        created_at:
          type: string
          format: date-time
          description: Timestamp when the variable was created
        updated_at:
          type: string
          format: date-time
          description: Timestamp when the variable was last updated
      required:
        - variable_id
        - key
        - value
      description: A template variable for agent configurations
      title: AgentVariableV1
    ErrorResponseTextError:
      type: string
      title: ErrorResponseTextError
    ErrorResponseLegacyError:
      type: object
      properties:
        err_code:
          type: string
          description: The error code
        err_msg:
          type: string
          description: The error message
        request_id:
          type: string
          description: The request ID
      title: ErrorResponseLegacyError
    ErrorResponseModernError:
      type: object
      properties:
        category:
          type: string
          description: The category of the error
        message:
          type: string
          description: A message about the error
        details:
          type: string
          description: A description of the error
        request_id:
          type: string
          description: The unique identifier of the request
      title: ErrorResponseModernError
    ErrorResponse:
      oneOf:
        - $ref: '#/components/schemas/ErrorResponseTextError'
        - $ref: '#/components/schemas/ErrorResponseLegacyError'
        - $ref: '#/components/schemas/ErrorResponseModernError'
      title: ErrorResponse
  securitySchemes:
    ApiKeyAuth:
      type: apiKey
      in: header
      name: Authorization
      description: |
        Use `Authorization: Token <API_KEY>`
        Example: `Authorization: Token 12345abcdef`

```

## SDK Code Examples

```python
import requests

url = "https://api.deepgram.com/v1/projects/project_id/agent-variables/variable_id"

payload = "https://api.deepgram.com/v1/projects/12345/agent-variables/67890"
headers = {
    "Authorization": "Token <apiKey>",
    "Content-Type": "application/json"
}

response = requests.patch(url, json=payload, headers=headers)

print(response.json())
```

```javascript
const url = 'https://api.deepgram.com/v1/projects/project_id/agent-variables/variable_id';
const options = {
  method: 'PATCH',
  headers: {Authorization: 'Token <apiKey>', 'Content-Type': 'application/json'},
  body: '"https://api.deepgram.com/v1/projects/12345/agent-variables/67890"'
};

try {
  const response = await fetch(url, options);
  const data = await response.json();
  console.log(data);
} catch (error) {
  console.error(error);
}
```

```go
package main

import (
	"fmt"
	"strings"
	"net/http"
	"io"
)

func main() {

	url := "https://api.deepgram.com/v1/projects/project_id/agent-variables/variable_id"

	payload := strings.NewReader("\"https://api.deepgram.com/v1/projects/12345/agent-variables/67890\"")

	req, _ := http.NewRequest("PATCH", url, payload)

	req.Header.Add("Authorization", "Token <apiKey>")
	req.Header.Add("Content-Type", "application/json")

	res, _ := http.DefaultClient.Do(req)

	defer res.Body.Close()
	body, _ := io.ReadAll(res.Body)

	fmt.Println(res)
	fmt.Println(string(body))

}
```

```ruby
require 'uri'
require 'net/http'

url = URI("https://api.deepgram.com/v1/projects/project_id/agent-variables/variable_id")

http = Net::HTTP.new(url.host, url.port)
http.use_ssl = true

request = Net::HTTP::Patch.new(url)
request["Authorization"] = 'Token <apiKey>'
request["Content-Type"] = 'application/json'
request.body = "\"https://api.deepgram.com/v1/projects/12345/agent-variables/67890\""

response = http.request(request)
puts response.read_body
```

```java
import com.mashape.unirest.http.HttpResponse;
import com.mashape.unirest.http.Unirest;

HttpResponse<String> response = Unirest.patch("https://api.deepgram.com/v1/projects/project_id/agent-variables/variable_id")
  .header("Authorization", "Token <apiKey>")
  .header("Content-Type", "application/json")
  .body("\"https://api.deepgram.com/v1/projects/12345/agent-variables/67890\"")
  .asString();
```

```php
<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('PATCH', 'https://api.deepgram.com/v1/projects/project_id/agent-variables/variable_id', [
  'body' => '"https://api.deepgram.com/v1/projects/12345/agent-variables/67890"',
  'headers' => [
    'Authorization' => 'Token <apiKey>',
    'Content-Type' => 'application/json',
  ],
]);

echo $response->getBody();
```

```csharp
using RestSharp;

var client = new RestClient("https://api.deepgram.com/v1/projects/project_id/agent-variables/variable_id");
var request = new RestRequest(Method.PATCH);
request.AddHeader("Authorization", "Token <apiKey>");
request.AddHeader("Content-Type", "application/json");
request.AddParameter("application/json", "\"https://api.deepgram.com/v1/projects/12345/agent-variables/67890\"", ParameterType.RequestBody);
IRestResponse response = client.Execute(request);
```

```swift
import Foundation

let headers = [
  "Authorization": "Token <apiKey>",
  "Content-Type": "application/json"
]
let parameters = "https://api.deepgram.com/v1/projects/12345/agent-variables/67890" as [String : Any]

let postData = JSONSerialization.data(withJSONObject: parameters, options: [])

let request = NSMutableURLRequest(url: NSURL(string: "https://api.deepgram.com/v1/projects/project_id/agent-variables/variable_id")! as URL,
                                        cachePolicy: .useProtocolCachePolicy,
                                    timeoutInterval: 10.0)
request.httpMethod = "PATCH"
request.allHTTPHeaderFields = headers
request.httpBody = postData as Data

let session = URLSession.shared
let dataTask = session.dataTask(with: request as URLRequest, completionHandler: { (data, response, error) -> Void in
  if (error != nil) {
    print(error as Any)
  } else {
    let httpResponse = response as? HTTPURLResponse
    print(httpResponse)
  }
})

dataTask.resume()
```

# Delete Agent Variable

DELETE https://api.deepgram.com/v1/projects/{project_id}/agent-variables/{variable_id}

Deletes the specified template variable

Reference: https://developers.deepgram.com/reference/voice-agent/agent-variables/delete-agent-variable

## OpenAPI Specification

```yaml
openapi: 3.1.0
info:
  title: Deepgram API Specification
  version: 1.0.0
paths:
  /v1/projects/{project_id}/agent-variables/{variable_id}:
    delete:
      operationId: delete
      summary: Delete an Agent Variable
      description: Deletes the specified template variable
      tags:
        - subpackage_voiceAgent.subpackage_voiceAgent/variables
      parameters:
        - name: project_id
          in: path
          required: true
          schema:
            type: string
        - name: variable_id
          in: path
          required: true
          schema:
            type: string
        - name: Authorization
          in: header
          description: |
            Use `Authorization: Token <API_KEY>`
            Example: `Authorization: Token 12345abcdef`
          required: true
          schema:
            type: string
      responses:
        '200':
          description: Agent variable deleted
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/DeleteAgentVariableV1Response'
        '400':
          description: Invalid Request
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/ErrorResponse'
servers:
  - url: https://api.deepgram.com
components:
  schemas:
    DeleteAgentVariableV1Response:
      type: object
      properties: {}
      description: Confirmation that the agent variable was deleted
      title: DeleteAgentVariableV1Response
    ErrorResponseTextError:
      type: string
      title: ErrorResponseTextError
    ErrorResponseLegacyError:
      type: object
      properties:
        err_code:
          type: string
          description: The error code
        err_msg:
          type: string
          description: The error message
        request_id:
          type: string
          description: The request ID
      title: ErrorResponseLegacyError
    ErrorResponseModernError:
      type: object
      properties:
        category:
          type: string
          description: The category of the error
        message:
          type: string
          description: A message about the error
        details:
          type: string
          description: A description of the error
        request_id:
          type: string
          description: The unique identifier of the request
      title: ErrorResponseModernError
    ErrorResponse:
      oneOf:
        - $ref: '#/components/schemas/ErrorResponseTextError'
        - $ref: '#/components/schemas/ErrorResponseLegacyError'
        - $ref: '#/components/schemas/ErrorResponseModernError'
      title: ErrorResponse
  securitySchemes:
    ApiKeyAuth:
      type: apiKey
      in: header
      name: Authorization
      description: |
        Use `Authorization: Token <API_KEY>`
        Example: `Authorization: Token 12345abcdef`

```

## SDK Code Examples

```python
import requests

url = "https://api.deepgram.com/v1/projects/project_id/agent-variables/variable_id"

payload = {}
headers = {
    "Authorization": "Token <apiKey>",
    "Content-Type": "application/json"
}

response = requests.delete(url, json=payload, headers=headers)

print(response.json())
```

```javascript
const url = 'https://api.deepgram.com/v1/projects/project_id/agent-variables/variable_id';
const options = {
  method: 'DELETE',
  headers: {Authorization: 'Token <apiKey>', 'Content-Type': 'application/json'},
  body: '{}'
};

try {
  const response = await fetch(url, options);
  const data = await response.json();
  console.log(data);
} catch (error) {
  console.error(error);
}
```

```go
package main

import (
	"fmt"
	"strings"
	"net/http"
	"io"
)

func main() {

	url := "https://api.deepgram.com/v1/projects/project_id/agent-variables/variable_id"

	payload := strings.NewReader("{}")

	req, _ := http.NewRequest("DELETE", url, payload)

	req.Header.Add("Authorization", "Token <apiKey>")
	req.Header.Add("Content-Type", "application/json")

	res, _ := http.DefaultClient.Do(req)

	defer res.Body.Close()
	body, _ := io.ReadAll(res.Body)

	fmt.Println(res)
	fmt.Println(string(body))

}
```

```ruby
require 'uri'
require 'net/http'

url = URI("https://api.deepgram.com/v1/projects/project_id/agent-variables/variable_id")

http = Net::HTTP.new(url.host, url.port)
http.use_ssl = true

request = Net::HTTP::Delete.new(url)
request["Authorization"] = 'Token <apiKey>'
request["Content-Type"] = 'application/json'
request.body = "{}"

response = http.request(request)
puts response.read_body
```

```java
import com.mashape.unirest.http.HttpResponse;
import com.mashape.unirest.http.Unirest;

HttpResponse<String> response = Unirest.delete("https://api.deepgram.com/v1/projects/project_id/agent-variables/variable_id")
  .header("Authorization", "Token <apiKey>")
  .header("Content-Type", "application/json")
  .body("{}")
  .asString();
```

```php
<?php
require_once('vendor/autoload.php');

$client = new \GuzzleHttp\Client();

$response = $client->request('DELETE', 'https://api.deepgram.com/v1/projects/project_id/agent-variables/variable_id', [
  'body' => '{}',
  'headers' => [
    'Authorization' => 'Token <apiKey>',
    'Content-Type' => 'application/json',
  ],
]);

echo $response->getBody();
```

```csharp
using RestSharp;

var client = new RestClient("https://api.deepgram.com/v1/projects/project_id/agent-variables/variable_id");
var request = new RestRequest(Method.DELETE);
request.AddHeader("Authorization", "Token <apiKey>");
request.AddHeader("Content-Type", "application/json");
request.AddParameter("application/json", "{}", ParameterType.RequestBody);
IRestResponse response = client.Execute(request);
```

```swift
import Foundation

let headers = [
  "Authorization": "Token <apiKey>",
  "Content-Type": "application/json"
]
let parameters = [] as [String : Any]

let postData = JSONSerialization.data(withJSONObject: parameters, options: [])

let request = NSMutableURLRequest(url: NSURL(string: "https://api.deepgram.com/v1/projects/project_id/agent-variables/variable_id")! as URL,
                                        cachePolicy: .useProtocolCachePolicy,
                                    timeoutInterval: 10.0)
request.httpMethod = "DELETE"
request.allHTTPHeaderFields = headers
request.httpBody = postData as Data

let session = URLSession.shared
let dataTask = session.dataTask(with: request as URLRequest, completionHandler: { (data, response, error) -> Void in
  if (error != nil) {
    print(error as Any)
  } else {
    let httpResponse = response as? HTTPURLResponse
    print(httpResponse)
  }
})

dataTask.resume()
```





