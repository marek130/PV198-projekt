#include "lwip/opt.h"
#include "lwip/debug.h"
#include "lwip/stats.h"
#include "lwip/udp.h"
#include "controlLEDUDP.h"
#include "peripherals.h"

#if LWIP_UDP

enum color{Red, Green, Blue};
static struct udp_pcb *pcb;

static void setColorOfLed(int red, int green, int blue) {
	  FTM_UpdatePwmDutycycle(FTM_1_PERIPHERAL, kFTM_Chnl_1, kFTM_EdgeAlignedPwm, green); // GREEN
      FTM_UpdatePwmDutycycle(FTM_2_PERIPHERAL, kFTM_Chnl_1, kFTM_EdgeAlignedPwm, blue); // BLUE
      FTM_UpdatePwmDutycycle(FTM_1_PERIPHERAL, kFTM_Chnl_5, kFTM_EdgeAlignedPwm, red); // RED
      FTM_1_PERIPHERAL->SYNC |= FTM_SYNC_SWSYNC_MASK; // Software triger to refresh registers
      FTM_2_PERIPHERAL->SYNC |= FTM_SYNC_SWSYNC_MASK; // Software triger to refresh registers
}

static void parseUDPpacket(void *arg, struct udp_pcb *upcb, struct pbuf *p,
                 const ip_addr_t *addr, u16_t port) {
  LWIP_UNUSED_ARG(arg);
  if (p != NULL) {
	  char *pointer;
	  int red     = 0;
	  int green   = 0;
	  int blue    = 0;
	  enum color farba = Red;

	  pointer = strtok(p->payload, ":");

	  while (pointer != NULL) {
		  switch (farba) {
		     case Red: {
		    	 red = atoi(pointer);
		    	 farba++;
		    	 break;
		     }
		     case Green: {
		    	 green = atoi(pointer);
		    	 farba++;
		    	 break;
		     }
		     case Blue: {
		    	 blue = atoi(pointer);
		    	 break;
		     }
		  }
		  pointer = strtok(NULL, ":");
	  }

	  setColorOfLed(red, green, blue);
      pbuf_free(p);
  }
}

void changeColorViaUDP(void) {
  pcb = udp_new_ip_type(IPADDR_TYPE_ANY);
  if (udpecho_raw_pcb != NULL) {
    err_t err;

    err = udp_bind(pcb, IP_ANY_TYPE, 7777);
    if (err == ERR_OK) {
      udp_recv(pcb, parseUDPpacket, NULL);
    } else {
      /* abort? output diagnostic? */
    }
  } else {
    /* abort? output diagnostic? */
  }
}

#endif /* LWIP_UDP */
