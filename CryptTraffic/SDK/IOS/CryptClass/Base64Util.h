//
//  Base64Util.h
//  ThuInfo
//
//  Created by WenHao on 12-10-30.
//  Copyright (c) 2012年 WenHao. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "GTMBase64.h"

@interface Base64Util : NSObject

+ (NSString*)encodeBase64:(NSString*)input;
+ (NSString*)decodeBase64:(NSString*)input;

@end
