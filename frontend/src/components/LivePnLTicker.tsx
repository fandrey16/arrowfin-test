import React, { useEffect, useState, useRef } from 'react';

interface PnLPayload {
  accountId: number;
  pnl: number;
  status: 'closed';
}

interface LivePnLTickerProps {
  socketUrl: string;
}

const LivePnLTicker: React.FC<LivePnLTickerProps> = ({ socketUrl }) => {
  const [pnl, setPnl] = useState<number>(0);
  const [isConnected, setIsConnected] = useState<boolean>(false);
  const wsRef = useRef<WebSocket | null>(null);

  useEffect(() => {
    // Connect to WebSocket
    const ws = new WebSocket(socketUrl);
    wsRef.current = ws;

    ws.onopen = () => {
      setIsConnected(true);
      console.log('WebSocket connected');
    };

    ws.onmessage = (event: MessageEvent) => {
      try {
        const payload: PnLPayload = JSON.parse(event.data);
        
        // Validate payload structure
        if (
          typeof payload.accountId === 'number' &&
          typeof payload.pnl === 'number' &&
          payload.status === 'closed'
        ) {
          setPnl(payload.pnl);
        }
      } catch (error) {
        console.error('Failed to parse WebSocket message:', error);
      }
    };

    ws.onerror = (error) => {
      console.error('WebSocket error:', error);
      setIsConnected(false);
    };

    ws.onclose = () => {
      setIsConnected(false);
      console.log('WebSocket disconnected');
    };

    // Cleanup: disconnect socket when component unmounts
    return () => {
      if (wsRef.current) {
        wsRef.current.close();
        wsRef.current = null;
      }
    };
  }, [socketUrl]);

  const getPnLColor = (): string => {
    if (pnl > 0) return 'text-green-500';
    if (pnl < 0) return 'text-red-500';
    return 'text-gray-500';
  };

  return (
    <div className="flex items-center gap-4 p-4 bg-gray-900 rounded-lg">
      <div className="flex items-center gap-2">
        <div
          className={`w-2 h-2 rounded-full transition-colors duration-300 ${
            isConnected ? 'bg-green-500' : 'bg-red-500'
          }`}
        />
        <span className="text-sm text-gray-400">
          {isConnected ? 'Live' : 'Disconnected'}
        </span>
      </div>
      
      <div className="flex flex-col">
        <span className="text-xs text-gray-400 uppercase tracking-wide">
          Profit/Loss
        </span>
        <span
          className={`text-2xl font-bold transition-colors duration-500 ${getPnLColor()}`}
        >
          ${pnl.toFixed(2)}
        </span>
      </div>
    </div>
  );
};

export default LivePnLTicker;
